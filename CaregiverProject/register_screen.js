        let currentStep = 0;
        showStep(currentStep);

        function showStep(n) {
            let steps = document.getElementsByClassName("step");
            steps[n].classList.add("active");
            
            if (n === 0) {
                document.getElementById("prevBtn").style.display = "none";
            } else {
                document.getElementById("prevBtn").style.display = "inline";
            }

            if (n === (steps.length - 1)) {
                document.getElementById("nextBtn").style.display = "none";
                document.getElementById("submitBtn").style.display = "inline";
            } else {
                document.getElementById("nextBtn").style.display = "inline";
                document.getElementById("submitBtn").style.display = "none";
            }
        }

        function nextPrev(n) {
            let steps = document.getElementsByClassName("step");
            steps[currentStep].classList.remove("active");
            currentStep = currentStep + n;

            if (currentStep >= steps.length) {
                document.getElementById("registrationForm").submit();
                return false;
            }

            showStep(currentStep);
        }