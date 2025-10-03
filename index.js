const messageForm = document.getElementById("message_form");

function uint8ArrayToBase64(u8) {
  let binary = "";
  for (let i = 0; i < u8.byteLength; i++) {
    binary += String.fromCharCode(u8[i]);
  }
  return btoa(binary);
}

messageForm.addEventListener("submit", (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  const message = formData.get("message");

  const otp = generateOtp();

  encryptMessage(message, otp)
    .then((encryptedData) => {
      const fd = new FormData();
      fd.append("encryptedMessage", encryptedData.encryptedMessage);
      fd.append("salt", encryptedData.salt);
      fd.append("iv", encryptedData.iv);

      fetch("submit.php", {
        method: "POST",
        body: fd,
      })
        .then((response) => response.json())
        .then((body) => {
          const secretUrl = `${window.location.origin}/secret-sharing-app/view.php?id=${body.id}`;
          alert(
            `Secret saved! Your OTP is: ${otp}. Secret URL is ${secretUrl}`,
          );
          form.reset();
        });

      // todo: display otp and url of the secret
    })
    .catch((error) => {
      console.error("Encryption failed:", error);
    });
});

async function encryptMessage(message, otp) {
  const encoder = new TextEncoder();
  const salt = crypto.getRandomValues(new Uint8Array(SALT_LENGTH));

  const key = await crypto.subtle.importKey(
    "raw",
    encoder.encode(otp),
    { name: KEY_ALGORITHM },
    false,
    ["deriveKey"],
  );

  const derivedKey = await crypto.subtle.deriveKey(
    {
      name: KEY_ALGORITHM,
      salt,
      iterations: 100000,
      hash: HASH_ALGORITHM,
    },
    key,
    { name: CIPHER_ALGORITHM, length: KEY_LENGTH },
    false,
    ["encrypt"],
  );

  const iv = crypto.getRandomValues(new Uint8Array(IV_LENGTH));

  const encryptedData = await crypto.subtle.encrypt(
    {
      name: CIPHER_ALGORITHM,
      iv,
    },
    derivedKey,
    encoder.encode(message),
  );

  return {
    encryptedMessage: uint8ArrayToBase64(new Uint8Array(encryptedData)),
    salt: uint8ArrayToBase64(salt),
    iv: uint8ArrayToBase64(iv),
  };
}

function generateOtp() {
  const randomNum = Math.random();
  const randomValue = Math.floor(randomNum * 9000) + 1000;
  return randomValue.toString();
}
