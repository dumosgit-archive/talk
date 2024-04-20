<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>404 - Page Not Found</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      overflow: hidden;
    }
    .container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      background: rgba(35, 35, 35, 0.6);
      border-radius: 30px;
      padding: 30px;
      color: white;
    }
    h1 {
      font-size: 5rem;
      color: white;
    }
    p {
      font-size: 1.5rem;
      color: white;
    }
    .globs {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
    }
    .globs canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }
    a {
        color: white;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>404</h1>
    <p>Page Not Found</p>
    <p id="text"></p>
    <p><a href="/">Go back to hell</a></p>
  </div>
  <div class="globs">
    <canvas id="globs-canvas"></canvas>
  </div>

  <script>
    let text = ["how do u even do that", "epic fail", "you tried", "there was an attempt", "its ok c:", "me rn: (•_•) ( •_•)>⌐■-■ (⌐■_■)"][6 * Math.random() | 0]
    document.getElementById("text").innerText = text;
  
    const canvas = document.getElementById('globs-canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    let globs = [];
    function createGlob() {
      return {
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: Math.random() * 20 + 5,
        color: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${Math.random() * 255}, 0.5)`,
        vx: Math.random() * 2 - 1,
        vy: Math.random() * 2 - 1
      };
    }
    function drawGlobs() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      globs.forEach(glob => {
        ctx.beginPath();
        ctx.arc(glob.x, glob.y, glob.radius, 0, Math.PI * 2);
        ctx.fillStyle = glob.color;
        ctx.fill();
      });
    }
    function updateGlobs() {
      globs.forEach(glob => {
        glob.x += glob.vx;
        glob.y += glob.vy;

        if (glob.x + glob.radius > canvas.width || glob.x - glob.radius < 0) {
          glob.vx = -glob.vx;
        }

        if (glob.y + glob.radius > canvas.height || glob.y - glob.radius < 0) {
          glob.vy = -glob.vy;
        }
      });
    }
    for (let i = 0; i < 30; i++) {
      globs.push(createGlob());
    }
    function animate() {
      requestAnimationFrame(animate);
      updateGlobs();
      drawGlobs();
    }
    animate();
    window.addEventListener('resize', () => {
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
    });
  </script>
</body>
</html>
