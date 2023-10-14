import 'dart:async';

import 'package:flutter/material.dart';
import 'music.dart';
import 'package:audioplayers/audioplayers.dart';

/*
Exercice n°1 :
L'excercice consiste en la conception d'un lecteur de fichier audio
Utilisation du package audioPlayers

 */

//App calling
void main() {
  runApp(const MyApp());
}

//App description
class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'App Music',
      theme: ThemeData(
        primarySwatch: Colors.blue,
      ),
      home: const MyHomePage(title: 'Archy Music'),
      debugShowCheckedModeBanner: false,
    );
  }
}

//Home page class
class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key, required this.title});

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

//How Homme page work
class _MyHomePageState extends State<MyHomePage> {

  //Construction de la liste de musique dans le dossier Assets/musics
  List<Music> myMusicList = [
    Music('King of the Hill', 'Dirty Palm & Nat James', 'assets/ncs1.jpg', AssetSource('musics/music1.mp3')),
    Music('Just Getting Started', 'Jim Yosef & Shiah Maisel', 'assets/ncs2.jpg', AssetSource('musics/music2.mp3')),
  ];

  //Variables necessaires
  late AudioPlayer audioPlayer;
  late StreamSubscription positionSub;
  late StreamSubscription stateSub;
  late Music myActualMusic;
  Duration musicDuration = new Duration(seconds: 0);
  Duration position = new Duration(seconds: 0);
  late int totalDuration;
  PlayerState status = PlayerState.stopped;
  late int index = 0;


  //Initialisation de la musique sur la piste 1
  @override void initState() {
    super.initState();
    myActualMusic = myMusicList[0];
    configurationAudioPlayer();
  }

  //Constructeur
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        centerTitle: true,
        title: Text(widget.title),
        backgroundColor: Colors.grey[900],
      ),
      backgroundColor: Colors.grey[800],
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: <Widget>[
            Card(
              elevation: 9.0,
              child: Container(
                width: MediaQuery.of(context).size.height / 3 ,
                child: Image.asset(myActualMusic.imagePath),
              ),
            ),
            textWithStyle(myActualMusic.title, 1.5),
            textWithStyle(myActualMusic.artist, 1.0),
            Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                buttonMusic(Icons.fast_rewind, 30.0, ActionMusic.rewind),
                buttonMusic((status == PlayerState.playing) ? Icons.pause:
                  Icons.play_arrow, 45.0, (status == PlayerState.playing) ?
                  ActionMusic.stop: ActionMusic.play),
                buttonMusic(Icons.fast_forward, 30.0, ActionMusic.forward)
              ],
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                textWithStyle(fromDuration(position), 0.8),
                textWithStyle(fromTotalDurationInt(totalDuration), 0.8)
              ],
            ),
            new Slider(
                value: position.inSeconds.toDouble(),
                min: 0.0,
                max: totalDuration.toDouble(),
                inactiveColor: Colors.white,
                activeColor: Colors.purple,
                onChanged: (double d){
                  setState(() {
                    audioPlayer.seek(Duration(seconds: d.toInt()));
                  });
                }
            )
          ],
        ),
      ),
    );
  }

  //Initialisation de la musique
  void configurationAudioPlayer(){
    audioPlayer = new AudioPlayer();
    positionSub = audioPlayer.onPositionChanged.listen(
            (pos) => setState(() => position = pos)
    );
    //total duration
    audioPlayer.onDurationChanged.listen((Duration d) {
      totalDuration = d.inSeconds.toInt();
      print('The total duration is : $totalDuration');
    });

    stateSub = audioPlayer.onPlayerStateChanged.listen((state) {
      if(state == PlayerState.playing) {
        setState(() {
          musicDuration = audioPlayer.getDuration() as Duration;
        });
      } else if (state == PlayerState.stopped) {
        setState(() {
          status = PlayerState.stopped;
        });
      }
    },
        onError: (message) {
          print('error: $message');
          setState(() {
            status = PlayerState.stopped;
            musicDuration = new Duration(seconds: 0);
            position = new Duration(seconds: 0);
          });
        }
    );
  }
  
  //Fonction pour afficher le temps en hh.mm.ss
  String fromTotalDurationInt(int t){
    //Initialisation des variables
    int hours = t ~/ 3600;
    int minutes = (t ~/ 60) - (t~/3600);
    int seconds = t % 60;

    return "$hours:${minutes.toString().padLeft(2, '0')}:${seconds.toString().padLeft(2, '0')}";
  }

  //Fonction pour transformer une Duration en String
  String fromDuration(Duration duree) {
    print(duree);
    return duree.toString().split('.').first;
  }

  //Affichage et fonction des boutons
  IconButton buttonMusic(IconData icon, double scale, ActionMusic action) {
    return new IconButton(
      iconSize: scale,
      color: Colors.white,
      icon: Icon(icon),
      onPressed: (){
        switch (action) {
          case ActionMusic.play:
            play();
            break;
          case ActionMusic.stop:
            pause();
            break;
          case ActionMusic.rewind:
            rewind();
            break;
          case ActionMusic.forward:
            forward();
            break;
        }
      },
    );
  }

  Text textWithStyle(String data, double scale) {
    return Text(
      data,
      textScaleFactor: scale,
      textAlign: TextAlign.center,
      style: TextStyle(
        color: Colors.white,
        fontSize: 20,
        fontStyle: FontStyle.italic
      ),
    );
  }

  /*
  Liste de fonctions pour l'utilisation des boutons
  Les fonctiones sont asynchrones pour permettre d'attendre l'appuie du bouton
  afin d'appeler la fonction
   */
  void forward() async{//Avancer
    if (index == myMusicList.length - 1){
      index = 0;
    } else {
      index++;
    }
    myActualMusic = myMusicList[index];
    audioPlayer.stop();
    configurationAudioPlayer();
    play();
  }

  void rewind() async{//Reculer
    if (position > Duration(seconds: 3)){
      audioPlayer.seek(Duration(seconds: 0));
    } else {
      if (index == 0) {
        index = myMusicList.length - 1;
      }else {
        index--;
      }
      myActualMusic = myMusicList[index];
      audioPlayer.stop();
      configurationAudioPlayer();
      play();
    }
  }

  Future play() async {
    await audioPlayer.play(myActualMusic.urlSong);
    setState(() {
      status = PlayerState.playing;
    });
  }

  Future pause() async {
    await audioPlayer.pause();
    setState(() {
      status = PlayerState.stopped;
    });
  }
}

enum ActionMusic {
  play,
  stop,
  rewind,
  forward
}

enum PlayerState {
  playing,
  stopped,
  paused
}
