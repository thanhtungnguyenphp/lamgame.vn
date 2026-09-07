(() => {
'use strict';
const boardNode=document.getElementById('board'),statusNode=document.getElementById('status'),turnNode=document.getElementById('turn');
const SYMBOL={K:'♔',Q:'♕',R:'♖',B:'♗',N:'♘',P:'♙',k:'♚',q:'♛',r:'♜',b:'♝',n:'♞',p:'♟'};
const VALUE={p:1,n:3,b:3,r:5,q:9,k:100};
let board,selected,legal,whiteTurn,ended,aiThinking;
const initial=()=>[
 ['r','n','b','q','k','b','n','r'],['p','p','p','p','p','p','p','p'],
 [null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],
 [null,null,null,null,null,null,null,null],[null,null,null,null,null,null,null,null],
 ['P','P','P','P','P','P','P','P'],['R','N','B','Q','K','B','N','R']
];
const white=p=>p&&p===p.toUpperCase(),inside=(r,c)=>r>=0&&r<8&&c>=0&&c<8,clone=b=>b.map(row=>row.slice());
function pseudo(b,r,c,attackOnly=false){const piece=b[r][c];if(!piece)return[];const isWhite=white(piece),type=piece.toLowerCase(),moves=[];const add=(nr,nc)=>{if(!inside(nr,nc))return false;const target=b[nr][nc];if(!target){moves.push({r:nr,c:nc});return true}if(white(target)!==isWhite)moves.push({r:nr,c:nc,capture:true});return false};
 if(type==='p'){const dir=isWhite?-1:1;if(attackOnly){for(const dc of[-1,1])if(inside(r+dir,c+dc))moves.push({r:r+dir,c:c+dc});return moves}if(inside(r+dir,c)&&!b[r+dir][c]){moves.push({r:r+dir,c});const start=isWhite?6:1;if(r===start&&!b[r+2*dir][c])moves.push({r:r+2*dir,c})}for(const dc of[-1,1])if(inside(r+dir,c+dc)&&b[r+dir][c+dc]&&white(b[r+dir][c+dc])!==isWhite)moves.push({r:r+dir,c:c+dc,capture:true});return moves}
 if(type==='n'){for(const[dr,dc]of[[2,1],[2,-1],[-2,1],[-2,-1],[1,2],[1,-2],[-1,2],[-1,-2]])add(r+dr,c+dc);return moves}
 if(type==='k'){for(let dr=-1;dr<=1;dr++)for(let dc=-1;dc<=1;dc++)if(dr||dc)add(r+dr,c+dc);return moves}
 const dirs=type==='b'?[[1,1],[1,-1],[-1,1],[-1,-1]]:type==='r'?[[1,0],[-1,0],[0,1],[0,-1]]:[[1,1],[1,-1],[-1,1],[-1,-1],[1,0],[-1,0],[0,1],[0,-1]];
 for(const[dr,dc]of dirs){let nr=r+dr,nc=c+dc;while(inside(nr,nc)){if(!add(nr,nc))break;nr+=dr;nc+=dc}}return moves}
function attacked(b,r,c,byWhite){for(let sr=0;sr<8;sr++)for(let sc=0;sc<8;sc++){const p=b[sr][sc];if(p&&white(p)===byWhite&&pseudo(b,sr,sc,true).some(m=>m.r===r&&m.c===c))return true}return false}
function inCheck(b,isWhite){for(let r=0;r<8;r++)for(let c=0;c<8;c++)if(b[r][c]===(isWhite?'K':'k'))return attacked(b,r,c,!isWhite);return true}
function apply(b,from,to){const next=clone(b),piece=next[from.r][from.c];next[to.r][to.c]=piece;next[from.r][from.c]=null;if(piece==='P'&&to.r===0)next[to.r][to.c]='Q';if(piece==='p'&&to.r===7)next[to.r][to.c]='q';return next}
function legalFrom(b,r,c){const piece=b[r][c];if(!piece)return[];const isWhite=white(piece);return pseudo(b,r,c).filter(move=>!inCheck(apply(b,{r,c},move),isWhite))}
function allMoves(b,isWhite){const moves=[];for(let r=0;r<8;r++)for(let c=0;c<8;c++)if(b[r][c]&&white(b[r][c])===isWhite)for(const to of legalFrom(b,r,c))moves.push({from:{r,c},to});return moves}
function render(){boardNode.innerHTML='';for(let r=0;r<8;r++)for(let c=0;c<8;c++){const square=document.createElement('button'),piece=board[r][c],move=legal.find(m=>m.r===r&&m.c===c);square.type='button';square.className=`square ${(r+c)%2?'dark':'light'}`;square.setAttribute('role','gridcell');square.setAttribute('aria-label',piece?`${piece} tại ${String.fromCharCode(97+c)}${8-r}`:`Ô ${String.fromCharCode(97+c)}${8-r}`);if(selected&&selected.r===r&&selected.c===c)square.classList.add('selected');if(move){square.classList.add('legal');if(move.capture)square.classList.add('capture')}if(piece){square.textContent=SYMBOL[piece];square.classList.add(white(piece)?'white-piece':'black-piece')}square.addEventListener('click',()=>choose(r,c));boardNode.appendChild(square)}turnNode.textContent=aiThinking?'AI đang nghĩ':whiteTurn?'Trắng':'Đen'}
function choose(r,c){if(ended||!whiteTurn||aiThinking)return;const move=legal.find(item=>item.r===r&&item.c===c);if(selected&&move){board=apply(board,selected,move);selected=null;legal=[];whiteTurn=false;finishTurn(false);return}const piece=board[r][c];if(piece&&white(piece)){selected={r,c};legal=legalFrom(board,r,c);statusNode.textContent=legal.length?'Chọn một ô được đánh dấu.':'Quân này không có nước hợp lệ.'}else{selected=null;legal=[]}render()}
function finishTurn(movedByAi){const side=whiteTurn;const moves=allMoves(board,side);if(!moves.length){ended=true;statusNode.textContent=inCheck(board,side)?`${side?'Trắng':'Đen'} bị chiếu hết — ${side?'AI':'Bạn'} thắng!`:'Ván cờ hòa do hết nước đi.';render();return}if(inCheck(board,side))statusNode.textContent=`${side?'Trắng':'Đen'} đang bị chiếu.`;else statusNode.textContent=movedByAi?'AI đã đi. Tới lượt bạn.':'AI đang chọn nước đi…';render();if(!whiteTurn)aiMove()}
function aiMove(){aiThinking=true;render();setTimeout(()=>{const moves=allMoves(board,false);let best=-Infinity,candidates=[];for(const move of moves){const target=board[move.to.r][move.to.c],next=apply(board,move.from,move.to);let score=(target?VALUE[target.toLowerCase()]*12:0)+Math.random()*2;if(inCheck(next,true))score+=3;if(score>best+.01){best=score;candidates=[move]}else if(Math.abs(score-best)<.01)candidates.push(move)}const choice=candidates[Math.floor(Math.random()*candidates.length)]||moves[0];if(choice)board=apply(board,choice.from,choice.to);aiThinking=false;whiteTurn=true;finishTurn(true)},450)}
function reset(){board=initial();selected=null;legal=[];whiteTurn=true;ended=false;aiThinking=false;statusNode.textContent='Trắng đi trước.';render()}
document.getElementById('restart').addEventListener('click',reset);reset();
})();
