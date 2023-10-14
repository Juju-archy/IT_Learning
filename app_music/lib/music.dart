import 'package:audioplayers/audioplayers.dart';

class Music{
  late String title;
  late String artist;
  late String imagePath;
  late Source urlSong;

  Music(String title, String artist, String imagePath, Source urlSong) {
    this.title = title;
    this.artist = artist;
    this.imagePath = imagePath;
    this.urlSong = urlSong;
  }

}