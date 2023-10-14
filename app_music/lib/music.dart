import 'package:audioplayers/audioplayers.dart';

//Music class
//Description : La class music permet de définir la composition et l'enplacement
//d'un fichier de musique

class Music{
  late String title;
  late String artist;
  late String imagePath;
  late Source urlSong;
  bool liked = false;

  Music(String title, String artist, String imagePath, Source urlSong) {
    this.title = title;
    this.artist = artist;
    this.imagePath = imagePath;
    this.urlSong = urlSong;
  }

}