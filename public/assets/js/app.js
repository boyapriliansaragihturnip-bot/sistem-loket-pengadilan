/**
 * Main JavaScript - Sistem Antrian Pengadilan
 * Mendukung AJAX/Fetch API dan Web Speech API Bahasa Indonesia (id-ID)
 */

document.addEventListener('DOMContentLoaded', function () {
    console.log('Sistem Antrian Pengadilan - Local LAN Active');
});

// Fungsi Text-to-Speech Web Speech API Bahasa Indonesia
function speakQueue(queueNumber, counterName) {
    if ('speechSynthesis' in window) {
        window.speechSynthesis.cancel(); // Hentikan antrean suara sebelumnya
        
        const text = `Nomor antrean ${queueNumber}, silakan menuju ${counterName}.`;
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'id-ID';
        utterance.rate = 0.9; // Kecepatan normal/jelas
        utterance.pitch = 1.0;

        window.speechSynthesis.speak(utterance);
    } else {
        console.warn('Web Speech API tidak didukung pada browser ini.');
    }
}