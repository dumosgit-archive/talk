<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dumos Chat</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
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

      #message {
        border: none;
        width: 450px;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <h1 style="display: flex">
        Dumos Chat
        <p style="font-size: 10px">v1</p>
      </h1>
      <textarea
        id="room"
        disabled
        style="height: 439px; width: 478px; resize: none"
      ></textarea>
      <form id="chatform">
        <input type="text" id="message" autocomplete="off" />
        <input type="submit" />
      </form>
      <p id="usernamechange" style="font-size:8px;cursor:pointer;">Change username</p>
    </div>
    <div class="globs">
      <canvas id="globs-canvas"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      let username;

      async function getUsername() {
        if (localStorage.getItem("DCRUsername") == null) {
          username = (await Swal.fire({
            title: "Username",
            text: "enter a username.",
            icon: "question",
            input: 'text',
            showClass: {
              popup: `
                animate__animated
                animate__fadeInUp
                animate__faster
            `,
            },
            hideClass: {
              popup: `
                animate__animated
                animate__fadeOutDown
                animate__faster
            `,
            },
            allowOutsideClick: false
          })).value;
          localStorage.setItem("DCRUsername", username);
        } else {
          username = localStorage.getItem("DCRUsername");
        }
      }

      getUsername();

      document.getElementById("chatform").addEventListener("submit", async (e) => {
        e.preventDefault();
        let msg = document.getElementById("message");
        let message = msg.value;
        msg.value = "";
        try {
            let response = await fetch(`https://talk.dumo.se/message?user=${encodeURIComponent(username)}&message=${encodeURIComponent(message)}`);
            let text = await response.text();
            if (!text == "Sent message successfully") {
                Swal.fire({
                title: "Error",
                text: "there was a issue sending your message. try again.",
                icon: "error",
                showClass: {
                popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                `,
                },
                hideClass: {
                popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                `,
                },
            });
        } else {console.log("[DCR] sent message successfully!")}
        } catch (ex) {
            Swal.fire({
                title: "Error",
                text: "there was a issue sending your message. try again.",
                icon: "error",
                showClass: {
                popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                `,
                },
                hideClass: {
                popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                `,
                },
            });
        }
      });

      setInterval(async () => {
        let response = await fetch("https://talk.dumo.se/room");
        let roomText = await response.text();
        let roomElem = document.getElementById("room");
        let scrollDown = false;

        if (!roomText != roomElem.value) {
            scrollDown = true;
        }

        roomElem.value = roomText;

        if (scrollDown) {
            roomElem.scrollTop = roomElem.scrollHeight;
        }
      }, 250)

      document.getElementById("usernamechange").addEventListener("click", () => {
        localStorage.removeItem("DCRUsername");
        location.reload();
      })

      const canvas = document.getElementById("globs-canvas");
      const ctx = canvas.getContext("2d");
      canvas.width = window.innerWidth;
      canvas.height = window.innerHeight;
      let globs = [];
      function createGlob() {
        return {
          x: Math.random() * canvas.width,
          y: Math.random() * canvas.height,
          radius: Math.random() * 20 + 5,
          color: `rgba(${Math.random() * 255}, ${Math.random() * 255}, ${
            Math.random() * 255
          }, 0.5)`,
          vx: Math.random() * 2 - 1,
          vy: Math.random() * 2 - 1,
        };
      }
      function drawGlobs() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        globs.forEach((glob) => {
          ctx.beginPath();
          ctx.arc(glob.x, glob.y, glob.radius, 0, Math.PI * 2);
          ctx.fillStyle = glob.color;
          ctx.fill();
        });
      }
      function updateGlobs() {
        globs.forEach((glob) => {
          glob.x += glob.vx;
          glob.y += glob.vy;

          if (glob.x + glob.radius > canvas.width || glob.x - glob.radius < 0) {
            glob.vx = -glob.vx;
          }

          if (
            glob.y + glob.radius > canvas.height ||
            glob.y - glob.radius < 0
          ) {
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
      window.addEventListener("resize", () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
      });
    </script>
  </body>
</html>
