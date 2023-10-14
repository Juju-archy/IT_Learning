import 'dart:async';

import 'package:flutter/material.dart';
import 'music.dart';
import 'package:audioplayers/audioplayers.dart';

void main() {
  runApp(const MyApp());
}

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

class MyHomePage extends StatefulWidget {
  const MyHomePage({super.key, required this.title});

  final String title;

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {

  List<Music> myMusicList = [
    Music('King of the Hill', 'Dirty Palm & Nat James', 'assets/ncs1.jpg', AssetSource('musics/music1.mp3')),
    Music('Just Getting Started', 'Jim Yosef & Shiah Maisel', 'assets/ncs2.jpg', AssetSource('musics/music2.mp3')),
  ];

  late AudioPlayer audioPlayer;
  late StreamSubscription positionSub;
  late StreamSubscription stateSub;
  late Music myActualMusic;
  Duration musicDuration = new Duration(seconds: 0);
  Duration position = new Duration(seconds: 0);
  PlayerState status = PlayerState.stopped;
  late int index = 0;

  @override void initState() {
    super.initState();
    myActualMusic = myMusicList[0];
    configurationAudioPlayer();
  }

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
                buttonMusic((status == PlayerState.playing) ? Icons.pause: Icons.play_arrow, 45.0, (status == PlayerState.playing) ? ActionMusic.stop: ActionMusic.play),
                buttonMusic(Icons.fast_forward, 30.0, ActionMusic.forward)
              ],
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: <Widget>[
                textWithStyle(fromDuration(position), 0.8),
                textWithStyle(fromDuration(musicDuration), 0.8)
              ],
            ),
            new Slider(
                value: position.inSeconds.toDouble(),
                min: 0.0,
                max: 30.0,
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

  String fromDuration(Duration duree) {
    print(duree);
    return duree.toString().split('.').first;
  }

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

  void forward() async{
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

  void rewind() async{
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

  void configurationAudioPlayer(){
    audioPlayer = new AudioPlayer();
    positionSub = audioPlayer.onPositionChanged.listen(
        (pos) => setState(() => position = pos)
    );
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

  Future play() async { //----------------------------------------------------
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
