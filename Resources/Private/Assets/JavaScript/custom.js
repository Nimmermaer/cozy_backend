document.addEventListener('DOMContentLoaded', () => {

  let deferredPrompt;
  const installButton = document.getElementById('installButton');
  if (!installButton) {
    return;
  }
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    installButton.hidden = false;
    console.log('beforeinstallprompt wurde ausgelöst und gespeichert.');
  });

  installButton.addEventListener('click', async () => {
    if (deferredPrompt) {
      installButton.hidden = true;
      deferredPrompt.prompt();
      const {outcome} = await deferredPrompt.userChoice;
      console.log(`Benutzerantwort: ${outcome}`);
      deferredPrompt = null;
    }
  });

});

