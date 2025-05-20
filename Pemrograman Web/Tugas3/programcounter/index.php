<!DOCTYPE html>
<html>
<head>
  <title>Counter Suara Angka</title>
  <style>
    body {
      background: #675234;
      font-family: sans-serif;
    }
    .container {
      width: 350px;
      padding: 20px;
      margin: 50px auto;
      background: #ffffbb;
      box-shadow: 0px 0px 20px #000;
      border: 2px solid #4d5368;
      border-radius: 20px;
      text-align: center;
    }
    .counter {
      width: 98%;
      border-radius: 5px;
      padding: 10px;
      font-size: 20px;
    }
    .navigasi {
      width: 45%;
      border-radius: 5px;
      padding: 10px;
      background: #764323;
      color: white;
      margin: 5px;
      font-size: 16px;
      cursor: pointer;
    }
    .bigNumber {
      font-size: 60px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div>
      <input type="number" id="counter" class="counter" value="0" min="0" max="9999999">
    </div>
    <div>
      <button onclick="changeValue(-1)" class="navigasi">Prev</button>
      <button onclick="changeValue(1)" class="navigasi">Next</button>
    </div>
    <div class="bigNumber" id="display">0</div>
  </div>

  <audio id="audioPlayer" style="display: none;"></audio>
  <script>
    const input = document.getElementById("counter");
    const display = document.getElementById("display");
    const audioPlayer = document.getElementById("audioPlayer");
    const audioPath = "audio/";
    const satuan = ["", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan"];

    let audioEnabled = false;
    document.body.addEventListener("click", () => audioEnabled = true, { once: true });

    function changeValue(delta) {
      let val = parseInt(input.value) || 0;
      val = Math.max(0, val + delta);
      input.value = val;
      update(val);
    }

    input.addEventListener("input", () => {
      const val = parseInt(input.value) || 0;
      update(val);
    });

    function update(val) {
      display.textContent = val;
      if (audioEnabled) {
        playAudioAngka(val);
      }
    }

    function playAudioAngka(angka) {
      const audioFiles = angkaKeAudio(angka);
      let index = 0;

      function playNext() {
        if (index < audioFiles.length) {
          audioPlayer.src = audioFiles[index];
          audioPlayer.play();
          index++;
        }
      }

      audioPlayer.onended = playNext;
      playNext();
    }

    function angkaKeAudio(angka) {
      const audio = [];

      if (angka >= 1_000_000) {
        const juta = Math.floor(angka / 1_000_000);
        audio.push(...angkaKeAudio(juta));
        audio.push(audioPath + "juta.mp3");
        angka %= 1_000_000;
      }

      if (angka >= 1000) {
        const ribu = Math.floor(angka / 1000);
        if (ribu === 1) {
          audio.push(audioPath + "seribu.mp3");
        } else {
          audio.push(...angkaKeAudio(ribu));
          audio.push(audioPath + "ribu.mp3");
        }
        angka %= 1000;
      }

      if (angka >= 100) {
        const ratus = Math.floor(angka / 100);
        if (ratus === 1) {
          audio.push(audioPath + "seratus.mp3");
        } else {
          audio.push(audioPath + satuan[ratus] + ".mp3");
          audio.push(audioPath + "ratus.mp3");
        }
        angka %= 100;
      }

      if (angka > 0 && angka < 10) {
        audio.push(audioPath + satuan[angka] + ".mp3");
      } else if (angka === 10) {
        audio.push(audioPath + "sepuluh.mp3");
      } else if (angka === 11) {
        audio.push(audioPath + "sebelas.mp3");
      } else if (angka < 20) {
        audio.push(audioPath + satuan[angka - 10] + ".mp3");
        audio.push(audioPath + "belas.mp3");
      } else if (angka < 100) {
        const puluh = Math.floor(angka / 10);
        const sisa = angka % 10;
        audio.push(audioPath + satuan[puluh] + ".mp3");
        audio.push(audioPath + "puluh.mp3");
        if (sisa > 0) {
          audio.push(audioPath + satuan[sisa] + ".mp3");
        }
      }

      return audio;
    }

    update(0);
  </script>
</body>
</html>
