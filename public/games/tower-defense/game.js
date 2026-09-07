(() => {
    'use strict';

    const canvas = document.getElementById('game');
    const ctx = canvas.getContext('2d');
    const nodes = {
        gold: document.getElementById('gold'), lives: document.getElementById('lives'),
        wave: document.getElementById('wave'), score: document.getElementById('score'),
        status: document.getElementById('status'), start: document.getElementById('start-wave'),
        upgrade: document.getElementById('upgrade'), restart: document.getElementById('restart'),
    };

    const path = [
        {x:-30,y:120},{x:180,y:120},{x:180,y:300},{x:420,y:300},
        {x:420,y:150},{x:690,y:150},{x:690,y:440},{x:990,y:440},
    ];
    const buildSpots = [];
    for (let y = 75; y <= 555; y += 80) {
        for (let x = 75; x <= 885; x += 90) {
            if (distanceToPath(x, y) > 58) buildSpots.push({x,y});
        }
    }

    let gold, lives, wave, score, towers, enemies, bullets, selected, spawning, spawnTimer, remaining, running, gameOver, lastTime;

    function distancePointToSegment(px, py, a, b) {
        const dx=b.x-a.x, dy=b.y-a.y;
        const length=dx*dx+dy*dy || 1;
        const t=Math.max(0,Math.min(1,((px-a.x)*dx+(py-a.y)*dy)/length));
        return Math.hypot(px-(a.x+t*dx),py-(a.y+t*dy));
    }
    function distanceToPath(x,y){let min=Infinity;for(let i=0;i<path.length-1;i++)min=Math.min(min,distancePointToSegment(x,y,path[i],path[i+1]));return min;}

    function reset() {
        gold=180; lives=20; wave=0; score=0; towers=[]; enemies=[]; bullets=[];
        selected=null; spawning=false; spawnTimer=0; remaining=0; running=true; gameOver=false;
        lastTime=performance.now();
        nodes.status.textContent='Đặt tháp rồi bắt đầu wave đầu tiên.';
        nodes.start.disabled=false; nodes.upgrade.disabled=true;
        syncHud();
    }

    function syncHud(){nodes.gold.textContent=gold;nodes.lives.textContent=lives;nodes.wave.textContent=wave;nodes.score.textContent=score;nodes.upgrade.disabled=!selected||gold<selected.upgradeCost||selected.level>=3;}

    function startWave(){
        if(spawning||enemies.length||gameOver)return;
        wave++;remaining=7+wave*3;spawning=true;spawnTimer=0;nodes.start.disabled=true;
        nodes.status.textContent=`Wave ${wave}: ${remaining} drone đang đến.`;syncHud();
    }

    function spawnEnemy(){
        const hp=45+wave*20;
        enemies.push({x:path[0].x,y:path[0].y,pathIndex:1,speed:48+wave*3,hp,maxHp:hp,reward:12+wave,radius:17});
    }

    function placeOrSelect(x,y){
        if(gameOver)return;
        const tower=towers.find(item=>Math.hypot(item.x-x,item.y-y)<30);
        if(tower){selected=tower;nodes.status.textContent=`Pulse Tower cấp ${tower.level} đã chọn.`;syncHud();return;}
        const spot=buildSpots.find(item=>Math.hypot(item.x-x,item.y-y)<28);
        if(!spot){selected=null;nodes.status.textContent='Chỉ có thể đặt tháp trên điểm xây dựng.';syncHud();return;}
        if(towers.some(item=>item.spot===spot)){return;}
        if(gold<50){nodes.status.textContent='Không đủ vàng để đặt tháp.';return;}
        gold-=50;
        selected={x:spot.x,y:spot.y,spot,level:1,range:128,damage:18,cooldown:0,fireRate:.62,upgradeCost:70};
        towers.push(selected);nodes.status.textContent='Đã đặt Pulse Tower.';syncHud();
    }

    function upgrade(){
        if(!selected||gold<selected.upgradeCost||selected.level>=3)return;
        gold-=selected.upgradeCost;selected.level++;selected.range+=22;selected.damage+=12;selected.fireRate-=.08;selected.upgradeCost+=45;
        nodes.status.textContent=`Tháp đã lên cấp ${selected.level}.`;syncHud();
    }

    function update(delta){
        if(!running||gameOver)return;
        if(spawning){spawnTimer-=delta;if(spawnTimer<=0&&remaining>0){spawnEnemy();remaining--;spawnTimer=Math.max(.35,1.05-wave*.035);}if(remaining===0)spawning=false;}
        for(const enemy of enemies){
            const target=path[enemy.pathIndex];
            const dx=target.x-enemy.x,dy=target.y-enemy.y,d=Math.hypot(dx,dy);
            if(d<=enemy.speed*delta){enemy.x=target.x;enemy.y=target.y;enemy.pathIndex++;if(enemy.pathIndex>=path.length){enemy.escaped=true;lives--;}}
            else{enemy.x+=dx/d*enemy.speed*delta;enemy.y+=dy/d*enemy.speed*delta;}
        }
        enemies=enemies.filter(enemy=>!enemy.escaped&&enemy.hp>0);
        for(const tower of towers){
            tower.cooldown-=delta;
            const target=enemies.filter(e=>Math.hypot(e.x-tower.x,e.y-tower.y)<=tower.range).sort((a,b)=>b.pathIndex-a.pathIndex)[0];
            tower.target=target||null;
            if(target&&tower.cooldown<=0){bullets.push({x:tower.x,y:tower.y,target,damage:tower.damage,speed:480});tower.cooldown=tower.fireRate;}
        }
        for(const bullet of bullets){
            if(!bullet.target||bullet.target.hp<=0){bullet.done=true;continue;}
            const dx=bullet.target.x-bullet.x,dy=bullet.target.y-bullet.y,d=Math.hypot(dx,dy);
            if(d<=bullet.speed*delta+bullet.target.radius){bullet.target.hp-=bullet.damage;bullet.done=true;if(bullet.target.hp<=0){gold+=bullet.target.reward;score+=100+wave*15;}}
            else{bullet.x+=dx/d*bullet.speed*delta;bullet.y+=dy/d*bullet.speed*delta;}
        }
        bullets=bullets.filter(b=>!b.done);
        enemies=enemies.filter(enemy=>enemy.hp>0);
        if(lives<=0){gameOver=true;running=false;nodes.start.disabled=true;nodes.status.textContent=`Lõi đã bị phá hủy. Điểm: ${score}.`;}
        if(!spawning&&remaining===0&&enemies.length===0&&wave>0&&!gameOver){nodes.start.disabled=false;nodes.status.textContent=`Wave ${wave} hoàn tất. Chuẩn bị phòng tuyến tiếp theo.`;}
        syncHud();
    }

    function drawPath(){
        ctx.lineCap='round';ctx.lineJoin='round';ctx.strokeStyle='#1e3a5f';ctx.lineWidth=58;ctx.beginPath();ctx.moveTo(path[0].x,path[0].y);for(const p of path.slice(1))ctx.lineTo(p.x,p.y);ctx.stroke();
        ctx.strokeStyle='#38bdf8';ctx.lineWidth=3;ctx.setLineDash([12,16]);ctx.stroke();ctx.setLineDash([]);
    }
    function draw(){
        const bg=ctx.createLinearGradient(0,0,0,canvas.height);bg.addColorStop(0,'#101c36');bg.addColorStop(1,'#08101f');ctx.fillStyle=bg;ctx.fillRect(0,0,canvas.width,canvas.height);
        ctx.strokeStyle='rgba(56,189,248,.07)';ctx.lineWidth=1;for(let x=0;x<canvas.width;x+=40){ctx.beginPath();ctx.moveTo(x,0);ctx.lineTo(x,canvas.height);ctx.stroke()}for(let y=0;y<canvas.height;y+=40){ctx.beginPath();ctx.moveTo(0,y);ctx.lineTo(canvas.width,y);ctx.stroke()}
        drawPath();
        for(const spot of buildSpots){const occupied=towers.some(t=>t.spot===spot);ctx.beginPath();ctx.arc(spot.x,spot.y,22,0,Math.PI*2);ctx.fillStyle=occupied?'rgba(15,23,42,.3)':'rgba(34,211,238,.12)';ctx.fill();ctx.strokeStyle=occupied?'rgba(148,163,184,.12)':'rgba(34,211,238,.4)';ctx.stroke();}
        for(const tower of towers){
            if(tower===selected){ctx.beginPath();ctx.arc(tower.x,tower.y,tower.range,0,Math.PI*2);ctx.fillStyle='rgba(34,211,238,.06)';ctx.fill();ctx.strokeStyle='rgba(34,211,238,.28)';ctx.stroke();}
            const angle=tower.target?Math.atan2(tower.target.y-tower.y,tower.target.x-tower.x):-Math.PI/2;
            ctx.save();ctx.translate(tower.x,tower.y);ctx.fillStyle=tower.level===3?'#fbbf24':'#22d3ee';ctx.beginPath();ctx.arc(0,0,21,0,Math.PI*2);ctx.fill();ctx.rotate(angle);ctx.fillStyle='#e0f2fe';ctx.fillRect(0,-6,31,12);ctx.restore();
            ctx.fillStyle='#082f49';ctx.font='800 13px system-ui';ctx.textAlign='center';ctx.fillText(String(tower.level),tower.x,tower.y+5);
        }
        for(const enemy of enemies){ctx.beginPath();ctx.arc(enemy.x,enemy.y,enemy.radius,0,Math.PI*2);ctx.fillStyle='#fb7185';ctx.fill();ctx.strokeStyle='#fecdd3';ctx.lineWidth=2;ctx.stroke();ctx.fillStyle='#1f2937';ctx.fillRect(enemy.x-20,enemy.y-27,40,5);ctx.fillStyle='#34d399';ctx.fillRect(enemy.x-20,enemy.y-27,40*Math.max(0,enemy.hp/enemy.maxHp),5);}
        for(const bullet of bullets){ctx.beginPath();ctx.arc(bullet.x,bullet.y,5,0,Math.PI*2);ctx.fillStyle='#fef08a';ctx.fill();}
        ctx.fillStyle='#60a5fa';ctx.beginPath();ctx.arc(930,440,24,0,Math.PI*2);ctx.fill();ctx.strokeStyle='#bfdbfe';ctx.lineWidth=5;ctx.stroke();
        if(gameOver){ctx.fillStyle='rgba(2,6,23,.8)';ctx.fillRect(0,0,canvas.width,canvas.height);ctx.fillStyle='#f8fafc';ctx.font='900 54px system-ui';ctx.textAlign='center';ctx.fillText('CORE OFFLINE',canvas.width/2,canvas.height/2-12);ctx.fillStyle='#7dd3fc';ctx.font='700 25px system-ui';ctx.fillText(`Điểm ${score}`,canvas.width/2,canvas.height/2+36);}
    }
    function frame(time){const delta=Math.min((time-lastTime)/1000,.033);lastTime=time;update(delta);draw();requestAnimationFrame(frame);}
    function pointer(event){const r=canvas.getBoundingClientRect();return{x:(event.clientX-r.left)*canvas.width/r.width,y:(event.clientY-r.top)*canvas.height/r.height};}
    canvas.addEventListener('pointerdown',event=>{const p=pointer(event);placeOrSelect(p.x,p.y);});
    nodes.start.addEventListener('click',startWave);nodes.upgrade.addEventListener('click',upgrade);nodes.restart.addEventListener('click',reset);
    reset();requestAnimationFrame(frame);
})();
