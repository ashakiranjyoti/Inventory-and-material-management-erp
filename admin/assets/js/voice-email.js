document.addEventListener('DOMContentLoaded', function() {
    const micBtn = document.getElementById('mic-btn');
    const languageSelector = document.getElementById('language');
    const commandOutput = document.getElementById('command-output');
    const emailList = document.getElementById('email-list');
    
    let recognition;
    
    // Initialize speech recognition
    function initSpeechRecognition() {
        recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition)();
        recognition.continuous = false;
        recognition.interimResults = false;
        
        recognition.onstart = function() {
            micBtn.classList.add('listening');
            commandOutput.innerHTML = '<em>Listening...</em>';
        };
        
        recognition.onresult = function(event) {
            const command = event.results[0][0].transcript;
            displayCommand(command);
            processCommand(command);
        };
        
        recognition.onerror = function(event) {
            displayError('Error occurred in recognition: ' + event.error);
            micBtn.classList.remove('listening');
        };
        
        recognition.onend = function() {
            micBtn.classList.remove('listening');
        };
    }
    
    // Display command in UI
    function displayCommand(command) {
        commandOutput.innerHTML = `<strong>You said:</strong> ${command}`;
    }
    
    // Display error
    function displayError(message) {
        commandOutput.innerHTML = `<strong class="error">Error:</strong> ${message}`;
    }
    
    // Process command with backend
    function processCommand(command) {
        const language = languageSelector.value;
        
        fetch('voice-email.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ command, language })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResponse(data.message);
                if (data.emails) {
                    displayEmails(data.emails);
                }
                speakResponse(data.message, language);
            } else {
                displayError(data.message);
            }
        })
        .catch(error => {
            displayError('Network error: ' + error.message);
        });
    }
    
    // Display response in UI
    function displayResponse(message) {
        commandOutput.innerHTML += `<br><strong>System:</strong> ${message}`;
    }
    
    // Display emails in UI
    function displayEmails(emails) {
        emailList.innerHTML = '';
        emails.forEach(email => {
            const emailItem = document.createElement('div');
            emailItem.className = 'email-item';
            emailItem.innerHTML = `
                <strong>From:</strong> ${email.sender_email}<br>
                <strong>Subject:</strong> ${email.subject}<br>
                <p>${email.message.substring(0, 100)}...</p>
            `;
            emailList.appendChild(emailItem);
        });
    }
    
    // Speak response
    function speakResponse(text, language) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = getLanguageCode(language);
        window.speechSynthesis.speak(utterance);
    }
    
    // Get proper language code
    function getLanguageCode(lang) {
        const codes = {
            'en': 'en-US',
            'hi': 'hi-IN',
            'es': 'es-ES'
        };
        return codes[lang] || 'en-US';
    }
    
    // Initialize on button click
    micBtn.addEventListener('click', function() {
        if (!recognition) {
            initSpeechRecognition();
        }
        
        if (micBtn.classList.contains('listening')) {
            recognition.stop();
        } else {
            recognition.lang = getLanguageCode(languageSelector.value);
            recognition.start();
        }
    });
});