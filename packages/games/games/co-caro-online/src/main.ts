import Phaser from 'phaser';
import { GAME_CONFIG } from '@lamgame/shared';
import { LobbyScene } from './scenes/LobbyScene';
import { GameScene } from './scenes/GameScene';

new Phaser.Game({
  ...GAME_CONFIG,
  scene: [LobbyScene, GameScene],
});
