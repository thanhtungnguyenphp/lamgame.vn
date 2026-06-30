import Phaser from 'phaser';
import { GAME_CONFIG, SplashScene, GameOverScene } from '@lamgame/shared';
import { GameScene } from './scenes/GameScene';

new Phaser.Game({ ...GAME_CONFIG, scene: [SplashScene, GameScene, GameOverScene] });
