function loadPage(page) {
    let url = `${page}.php`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.text();
        })
        .then(data => {
            document.getElementById('install').innerHTML = data;
            addEventListeners(page);
            appendFormDataToUrl(page);
        })
        .catch(error => {
            console.error('Fetch error:', error);
        });
}

// Function to append form data to the URL
function appendFormDataToUrl(page) {
    const formElement = document.getElementById(`${page}Form`);
    if (formElement) {
        const formData = new FormData(formElement);
        url += '?' + new URLSearchParams(formData).toString();
    } else {
        console.error('Form element not found');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Load the initial page
    loadPage('step1');

    // Event listener for the "Next" button
    document.addEventListener('click', function (event) {
        const target = event.target;
        if (target.tagName === 'BUTTON' && target.classList.contains('nextButton')) {
            handleNextButtonClick();
        }
    });
});

function addEventListeners(page) {
    // Event listeners for form submissions
    const form = document.getElementById(`${page}Form`);
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitForm(form, `database_install.php?step=${page}`);
        });
    }

    // Add more event listeners based on the page, if needed
}

function postData(url = '', data = {}) {

    return fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json', // Update the Content-Type
        },
        body: JSON.stringify(data),
    })
    .then(response => response.json())
    .catch(error => console.error('Error:', error));
}

function validateFormInputs(...inputs) {
    return inputs.every(input => input.trim() !== '');
}

// Function to handle form submission
function submitForm(form, action) {
    const formData = new FormData(form);

    // Validate the form data
    const requiredFields = Array.from(form.querySelectorAll('[required]'));
    const isValid = validateFormInputs(...requiredFields.map(field => formData.get(field.name)));

    if (isValid) {
        // Make the fetch request
        postData(action, Object.fromEntries(formData))
            .then(data => {
                if (data.success) {
                    const currentPage = getCurrentPage();
                    const nextPage = getNextPage(currentPage);
                    loadPage(nextPage);
                } else {
                    displayErrorMessage('errorMessages', data.message, 'warning');
                }
            })
            .catch(error => {
                console.error('Error submitting form:', error);
            });
    } else {
        displayErrorMessage('errorMessages', 'Please fill in all required fields.', 'warning');
    }
}

function handleNextButtonClick() {
    // Determine the current page based on the loaded content
    const currentPage = getCurrentPage();
    // Determine the next page based on the current page
    const nextPage = getNextPage(currentPage);
    // Load the next page
    loadPage(nextPage);
}

function getCurrentPage() {
    // Look for elements with class 'install-script'
    const installDivs = document.getElementsByClassName('install-script');

    // Check if any elements are found
    if (installDivs.length > 0) {
        // This assumes that each step page has a unique id
        return installDivs[0].id;
    }
    else {
        // Default to 'step1' if no elements with class 'install-script' are found
        return 'step1';
    }
}

function getNextPage(currentPage) {
    // Logic to determine the next page based on the current page
    switch (currentPage) {
        case 'step1':
            return 'step2';
        case 'step2':
            return 'step3';
        case 'step3':
            return 'step4';
        case 'step4':
            return 'step5';
        case 'step5':
            return 'step6';
        // Add more cases if needed
        default:
            return '';
    }
}

// Function to check the database connection
function checkDatabaseConnection() {
    const formData = new FormData(document.getElementById('mysqlForm'));

    fetch('database_install.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.text(); // Change to text() to see the raw response
    })
    .then(data => {

        // Parse the JSON response
        const jsonData = JSON.parse(data);

        if (jsonData.success) {
            // Database connection is successful, proceed to the next page
            loadPage('step3');
        } else {
            // Database connection failed, display error message
            displayErrorMessage('errorMessages', jsonData.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('errorMessages').innerHTML = 'An error occurred while checking the database connection.';
    });
}

// Function to display error messages in a stylized way
function displayErrorMessage(targetId, message, alertType = 'danger') {
    const errorDiv = document.createElement('div');
    errorDiv.classList.add('alert', `alert-${alertType}`, 'mx-auto', 'col-8', 'text-center');
    errorDiv.setAttribute('role', 'alert');

    // Create a <strong> element for the bold text
    const strongElement = document.createElement('strong');
    strongElement.innerText = message;

    // Append the <strong> element to the errorDiv
    errorDiv.appendChild(strongElement);

    // Clear previous error messages
    document.getElementById(targetId).innerHTML = '';

    // Append the new error message
    document.getElementById(targetId).appendChild(errorDiv);
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirmpassword').value;

    if (password !== confirmPassword) {
        displayErrorMessage('errorMessages', 'Your passwords don\'t match', 'warning');
        return false;
    }

    // If passwords match, you can proceed with form submission
    const form = document.getElementById('step3Form'); // Replace with your actual form ID
    submitForm(form, 'step3Form'); // Call your form submission function
}