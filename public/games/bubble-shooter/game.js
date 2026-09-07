(() => {
    'use strict';

    const canvas = document.getElementById('game');
    const context = canvas.getContext('2d');
    const scoreNode = document.getElementById('score');
    const bestNode = document.getElementById('best');
    const missesNode = document.getElementById('misses');
    const nextNode = document.getElementById('next-bubble');
    const statusNode = document.getElementById('status');
    const restartButton = document.getElementById('restart');

    const WIDTH = canvas.width;
    const HEIGHT = canvas.height;
    const RADIUS = 27;
    const DIAMETER = RADIUS * 2;
    const COLUMN_GAP = 6;
    const ROW_GAP = 4;
    const CELL_X = DIAMETER + COLUMN_GAP;
    const CELL_Y = 48;
    const COLUMNS = 11;
    const MAX_ROWS = 16;
    const BOARD_LEFT = (WIDTH - ((COLUMNS - 1) * CELL_X + DIAMETER + CELL_X / 2)) / 2;
    const BOARD_TOP = 76;
    const SHOOTER = { x: WIDTH / 2, y: HEIGHT - 78 };
    const COLORS = ['#fb7185', '#38bdf8', '#34d399', '#fbbf24', '#a78bfa'];

    let grid;
    let currentColor;
    let nextColor;
    let projectile;
    let aimAngle;
    let score;
    let bestCombo;
    let misses;
    let gameOver;
    let lastTime;

    function randomColor() {
        const available = [...new Set(grid.flat().filter(Boolean).map(bubble => bubble.color))];
        const palette = available.length ? available : COLORS;
        return palette[Math.floor(Math.random() * palette.length)];
    }

    function cellPosition(row, column) {
        return {
            x: BOARD_LEFT + RADIUS + column * CELL_X + (row % 2 ? CELL_X / 2 : 0),
            y: BOARD_TOP + RADIUS + row * CELL_Y,
        };
    }

    function createBubble(row, column, color) {
        const position = cellPosition(row, column);
        return { row, column, color, x: position.x, y: position.y };
    }

    function reset() {
        grid = Array.from({ length: MAX_ROWS }, () => Array(COLUMNS).fill(null));
        for (let row = 0; row < 6; row++) {
            for (let column = 0; column < COLUMNS - (row % 2); column++) {
                grid[row][column] = createBubble(row, column, COLORS[Math.floor(Math.random() * COLORS.length)]);
            }
        }
        currentColor = randomColor();
        nextColor = randomColor();
        projectile = null;
        aimAngle = -Math.PI / 2;
        score = 0;
        bestCombo = 0;
        misses = 0;
        gameOver = false;
        lastTime = performance.now();
        statusNode.textContent = 'Ghép ít nhất 3 bóng cùng màu.';
        syncHud();
    }

    function syncHud() {
        scoreNode.textContent = String(score);
        bestNode.textContent = String(bestCombo);
        missesNode.textContent = String(misses);
        nextNode.style.backgroundColor = nextColor;
    }

    function neighbours(row, column) {
        const offsets = row % 2
            ? [[-1, 0], [-1, 1], [0, -1], [0, 1], [1, 0], [1, 1]]
            : [[-1, -1], [-1, 0], [0, -1], [0, 1], [1, -1], [1, 0]];
        return offsets
            .map(([dr, dc]) => [row + dr, column + dc])
            .filter(([r, c]) => r >= 0 && r < MAX_ROWS && c >= 0 && c < COLUMNS);
    }

    function flood(startRow, startColumn, sameColor = true) {
        const origin = grid[startRow]?.[startColumn];
        if (!origin) return [];
        const result = [];
        const queue = [[startRow, startColumn]];
        const visited = new Set();
        while (queue.length) {
            const [row, column] = queue.shift();
            const key = `${row}:${column}`;
            if (visited.has(key)) continue;
            visited.add(key);
            const bubble = grid[row]?.[column];
            if (!bubble || (sameColor && bubble.color !== origin.color)) continue;
            result.push(bubble);
            for (const neighbour of neighbours(row, column)) queue.push(neighbour);
        }
        return result;
    }

    function removeFloating() {
        const anchored = new Set();
        const queue = [];
        for (let column = 0; column < COLUMNS; column++) {
            if (grid[0][column]) queue.push([0, column]);
        }
        while (queue.length) {
            const [row, column] = queue.shift();
            const key = `${row}:${column}`;
            if (anchored.has(key) || !grid[row]?.[column]) continue;
            anchored.add(key);
            for (const neighbour of neighbours(row, column)) queue.push(neighbour);
        }
        let removed = 0;
        for (let row = 0; row < MAX_ROWS; row++) {
            for (let column = 0; column < COLUMNS; column++) {
                if (grid[row][column] && !anchored.has(`${row}:${column}`)) {
                    grid[row][column] = null;
                    removed++;
                }
            }
        }
        return removed;
    }

    function addRow() {
        for (let row = MAX_ROWS - 1; row > 0; row--) {
            for (let column = 0; column < COLUMNS; column++) {
                const bubble = grid[row - 1][column];
                grid[row][column] = bubble ? createBubble(row, column, bubble.color) : null;
            }
        }
        for (let column = 0; column < COLUMNS; column++) {
            grid[0][column] = createBubble(0, column, COLORS[Math.floor(Math.random() * COLORS.length)]);
        }
        if (grid.slice(MAX_ROWS - 3).some(row => row.some(Boolean))) endGame();
    }

    function nearestEmpty(x, y) {
        let best = null;
        let bestDistance = Infinity;
        for (let row = 0; row < MAX_ROWS; row++) {
            for (let column = 0; column < COLUMNS; column++) {
                if (grid[row][column] || (row % 2 && column === COLUMNS - 1)) continue;
                const position = cellPosition(row, column);
                const distance = Math.hypot(position.x - x, position.y - y);
                const supported = row === 0 || neighbours(row, column).some(([r, c]) => grid[r][c]);
                if (supported && distance < bestDistance) {
                    best = { row, column, ...position };
                    bestDistance = distance;
                }
            }
        }
        return best;
    }

    function attachProjectile() {
        const target = nearestEmpty(projectile.x, projectile.y);
        if (!target) {
            endGame();
            projectile = null;
            return;
        }
        grid[target.row][target.column] = createBubble(target.row, target.column, projectile.color);
        const match = flood(target.row, target.column, true);
        if (match.length >= 3) {
            for (const bubble of match) grid[bubble.row][bubble.column] = null;
            const floating = removeFloating();
            const combo = match.length + floating;
            score += match.length * 10 + floating * 20;
            bestCombo = Math.max(bestCombo, combo);
            misses = 0;
            statusNode.textContent = `Đã xóa ${match.length} bóng${floating ? ` và thả ${floating} bóng` : ''}.`;
        } else {
            misses++;
            statusNode.textContent = 'Chưa đủ ba bóng cùng màu.';
            if (misses >= 5) {
                misses = 0;
                addRow();
                statusNode.textContent = 'Hàng mới đã xuất hiện.';
            }
        }
        projectile = null;
        currentColor = nextColor;
        nextColor = randomColor();
        syncHud();
        if (!grid.some(row => row.some(Boolean))) {
            score += 1000;
            statusNode.textContent = 'Bạn đã dọn sạch bàn! Nhấn Chơi lại để bắt đầu ván mới.';
            endGame(true);
        }
    }

    function shoot() {
        if (projectile || gameOver) return;
        const speed = 720;
        projectile = {
            x: SHOOTER.x,
            y: SHOOTER.y,
            color: currentColor,
            vx: Math.cos(aimAngle) * speed,
            vy: Math.sin(aimAngle) * speed,
        };
    }

    function endGame(cleared = false) {
        gameOver = true;
        statusNode.textContent = cleared ? statusNode.textContent : `Game over — điểm của bạn: ${score}.`;
    }

    function update(delta) {
        if (!projectile || gameOver) return;
        projectile.x += projectile.vx * delta;
        projectile.y += projectile.vy * delta;
        if (projectile.x <= RADIUS || projectile.x >= WIDTH - RADIUS) {
            projectile.x = Math.max(RADIUS, Math.min(WIDTH - RADIUS, projectile.x));
            projectile.vx *= -1;
        }
        let collision = projectile.y <= BOARD_TOP + RADIUS;
        if (!collision) {
            outer: for (const row of grid) {
                for (const bubble of row) {
                    if (bubble && Math.hypot(projectile.x - bubble.x, projectile.y - bubble.y) <= DIAMETER - 3) {
                        collision = true;
                        break outer;
                    }
                }
            }
        }
        if (collision) attachProjectile();
    }

    function drawBubble(x, y, color, radius = RADIUS) {
        const gradient = context.createRadialGradient(x - radius * .35, y - radius * .4, radius * .1, x, y, radius);
        gradient.addColorStop(0, '#ffffff');
        gradient.addColorStop(.18, color);
        gradient.addColorStop(1, shade(color, -30));
        context.beginPath();
        context.arc(x, y, radius, 0, Math.PI * 2);
        context.fillStyle = gradient;
        context.fill();
        context.strokeStyle = 'rgba(255,255,255,.45)';
        context.lineWidth = 2;
        context.stroke();
    }

    function shade(hex, amount) {
        const value = parseInt(hex.slice(1), 16);
        const r = Math.max(0, Math.min(255, (value >> 16) + amount));
        const g = Math.max(0, Math.min(255, ((value >> 8) & 255) + amount));
        const b = Math.max(0, Math.min(255, (value & 255) + amount));
        return `rgb(${r},${g},${b})`;
    }

    function draw() {
        context.clearRect(0, 0, WIDTH, HEIGHT);
        const background = context.createLinearGradient(0, 0, 0, HEIGHT);
        background.addColorStop(0, '#17194a');
        background.addColorStop(1, '#090b1f');
        context.fillStyle = background;
        context.fillRect(0, 0, WIDTH, HEIGHT);

        context.fillStyle = 'rgba(99,102,241,.13)';
        for (let y = 40; y < HEIGHT; y += 80) {
            context.fillRect(0, y, WIDTH, 1);
        }

        for (const row of grid) {
            for (const bubble of row) if (bubble) drawBubble(bubble.x, bubble.y, bubble.color);
        }

        if (!projectile && !gameOver) {
            context.save();
            context.setLineDash([10, 12]);
            context.strokeStyle = 'rgba(255,255,255,.55)';
            context.lineWidth = 3;
            context.beginPath();
            context.moveTo(SHOOTER.x, SHOOTER.y);
            context.lineTo(SHOOTER.x + Math.cos(aimAngle) * 190, SHOOTER.y + Math.sin(aimAngle) * 190);
            context.stroke();
            context.restore();
        }

        drawBubble(projectile?.x ?? SHOOTER.x, projectile?.y ?? SHOOTER.y, projectile?.color ?? currentColor, 30);
        context.fillStyle = '#e2e8f0';
        context.font = '700 22px system-ui';
        context.textAlign = 'center';
        context.fillText('NGẮM • BẮN • GHÉP 3', WIDTH / 2, HEIGHT - 24);

        if (gameOver) {
            context.fillStyle = 'rgba(5,7,20,.78)';
            context.fillRect(0, 0, WIDTH, HEIGHT);
            context.fillStyle = '#f8fafc';
            context.font = '800 52px system-ui';
            context.fillText('GAME OVER', WIDTH / 2, HEIGHT / 2 - 26);
            context.fillStyle = '#c4b5fd';
            context.font = '700 28px system-ui';
            context.fillText(`Điểm: ${score}`, WIDTH / 2, HEIGHT / 2 + 28);
        }
    }

    function frame(time) {
        const delta = Math.min((time - lastTime) / 1000, .033);
        lastTime = time;
        update(delta);
        draw();
        requestAnimationFrame(frame);
    }

    function setAim(clientX, clientY) {
        const rect = canvas.getBoundingClientRect();
        const x = (clientX - rect.left) * WIDTH / rect.width;
        const y = (clientY - rect.top) * HEIGHT / rect.height;
        const angle = Math.atan2(y - SHOOTER.y, x - SHOOTER.x);
        aimAngle = Math.max(-Math.PI + .18, Math.min(-.18, angle));
    }

    canvas.addEventListener('pointermove', event => setAim(event.clientX, event.clientY));
    canvas.addEventListener('pointerdown', event => {
        setAim(event.clientX, event.clientY);
        shoot();
    });
    window.addEventListener('keydown', event => {
        if (event.key === 'ArrowLeft') aimAngle = Math.max(-Math.PI + .18, aimAngle - .08);
        if (event.key === 'ArrowRight') aimAngle = Math.min(-.18, aimAngle + .08);
        if (event.code === 'Space') {
            event.preventDefault();
            shoot();
        }
    });
    restartButton.addEventListener('click', reset);

    reset();
    requestAnimationFrame(frame);
})();
