let warningTimeout = 5000;
let warningTimerID;
let counterDisplay = document.getElementById('numCount');
logoutUrl = "https://sacco.terrence-aluda.com/sacco/eng-edtest.html";

function startTimer() {
  // window.setTimeout returns an ID that can be used to start and stop the timer
  warningTimerID = window.setTimeout(idleLogout, warningTimeout);
  animate(counterDisplay, 5, 0, warningTimeout);
}
  //function for resetting the timer
function resetTimer() {
  window.clearTimeout(warningTimerID);
  startTimer();
}

// Logout the user.
function idleLogout() {
  window.location = logoutUrl;
}

function startCountdown() {}
  document.addEventListener("mousemove", resetTimer);
  document.addEventListener("mousedown", resetTimer);
  document.addEventListener("keypress", resetTimer);
  document.addEventListener("touchmove", resetTimer);
  document.addEventListener("onscroll", resetTimer);
  document.addEventListener("wheel", resetTimer);
  startTimer();
}
 //the animating function
    function animate(obj, initVal, lastVal, duration) {

      let startTime = null;

      //get the current timestamp and assign it to the currentTime variable

      let currentTime = Date.now();

      //pass the current timestamp to the step function

      const step = (currentTime ) => {

      //if the start time is null, assign the current time to startTime

          if (!startTime) {
          startTime = currentTime ;
          }

      //calculate the value to be used in calculating the number to be displayed

          const progress = Math.min((currentTime  - startTime) / duration, 1);

      //calculate what is to be displayed using the value gotten above

          displayValue = Math.floor(progress * (lastVal - initVal) + initVal);
          obj.innerHTML = displayValue;

      //checking to make sure the counter does not exceed the last value(lastVal)

          if (progress < 1) {
              window.requestAnimationFrame(step);
          }else{
              window.cancelAnimationFrame(window.requestAnimationFrame(step));
          }
      };

      //start animating
      window.requestAnimationFrame(step);
  }