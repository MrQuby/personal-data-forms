document.addEventListener('DOMContentLoaded', function() {
    // Handle civil status others visibility
    const civilStatus = document.getElementById('civil_status');
    const othersContainer = document.getElementById('others_container');
    
    if (civilStatus && othersContainer) {
        civilStatus.addEventListener('change', function() {
            othersContainer.style.display = this.value === 'others' ? 'block' : 'none';
        });
    }

    // Display age if date of birth is set
    const dobInput = document.getElementById('date_of_birth');
    const ageDisplay = document.getElementById('age_display');
    const calculatedAgeInput = document.getElementById('calculated_age');
    
    if (dobInput && ageDisplay) {
        dobInput.addEventListener('change', function() {
            if (this.value) {
                const dob = new Date(this.value);
                const today = new Date('2025-02-13');
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                // Update both display and hidden input
                ageDisplay.textContent = `Age: ${age} years old`;
                if (calculatedAgeInput) {
                    calculatedAgeInput.value = `Age: ${age} years old`;
                }
            } else {
                ageDisplay.textContent = '';
                if (calculatedAgeInput) {
                    calculatedAgeInput.value = '';
                }
            }
        });
    }
});

// Add smooth scroll animation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});