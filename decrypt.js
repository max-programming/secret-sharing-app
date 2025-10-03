function base64ToUint8Array(base64) {
  const binary = atob(base64);
  const len = binary.length;
  const bytes = new Uint8Array(len);
  for (let i = 0; i < len; i++) {
    bytes[i] = binary.charCodeAt(i);
  }
  return bytes;
}

async function decryptMessage(encryptedData, otp) {
  encryptedData.encryptedMessage = base64ToUint8Array(
    encryptedData.encryptedMessage,
  );
  encryptedData.salt = base64ToUint8Array(encryptedData.salt);
  encryptedData.iv = base64ToUint8Array(encryptedData.iv);

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
      salt: new Uint8Array(encryptedData.salt),
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
      iv: new Uint8Array(encryptedData.iv),
    },
    derivedKey,
    new Uint8Array(encryptedData.encryptedMessage),
  );

  return decoder.decode(decryptedData);
}
