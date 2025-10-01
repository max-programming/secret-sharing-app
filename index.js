const messageForm = document.getElementById("message_form");

messageForm.addEventListener("submit", (event) => {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);

  const message = formData.get("message");

  const otp = generateOtp();

  encryptMessage(message, otp)
    .then((encryptedData) => {
      const encryptedMessage = new Uint8Array(encryptedData.encryptedMessage);
      const salt = new Uint8Array(encryptedData.salt);
      const iv = new Uint8Array(encryptedData.iv);

      const fd = new FormData();
      fd.append("encryptedMessage", encryptedMessage);
      fd.append("salt", salt);
      fd.append("iv", iv);

      fetch("submit.php", {
        method: "POST",
        body: fd,
      });

      // todo: display otp and url of the secret
    })
    .catch((error) => {
      console.error("Encryption failed:", error);
    });
});

const ITERATIONS = 100000;
const SALT_LENGTH = 16;
const IV_LENGTH = 12;
const KEY_LENGTH = 256;
const KEY_ALGORITHM = "PBKDF2";
const CIPHER_ALGORITHM = "AES-GCM";
const HASH_ALGORITHM = "SHA-256";

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
    encryptedMessage: new Uint8Array(encryptedData),
    salt,
    iv,
  };
}

async function decryptMessage(encryptedWhim, otp) {
  const encoder = new TextEncoder();
  const decoder = new TextDecoder();

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
      salt: new Uint8Array(encryptedWhim.salt),
      iterations: ITERATIONS,
      hash: HASH_ALGORITHM,
    },
    key,
    { name: CIPHER_ALGORITHM, length: KEY_LENGTH },
    false,
    ["decrypt"],
  );

  const decryptedData = await crypto.subtle.decrypt(
    {
      name: CIPHER_ALGORITHM,
      iv: new Uint8Array(encryptedWhim.iv),
    },
    derivedKey,
    new Uint8Array(encryptedWhim.encryptedMessage),
  );

  return decoder.decode(decryptedData);
}

function generateOtp() {
  const randomNum = Math.random();
  const randomValue = Math.floor(randomNum * 9000) + 1000;
  return randomValue.toString();
}
