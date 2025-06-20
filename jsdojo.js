
const increaseBtn = document.getElementById("increaseBtn");
const decreaseBtn = document.getElementById("decreaseBtn");
const resetBtn = document.getElementById("resetBtn");
const countLabel = document.getElementById("countLabel");
const counterContainer = document.getElementById("counterContainer");
const counterBtn = document.getElementById("counterBtn");
let count = 0;


counterBtn.onclick = function(){
    hideDivBtn(counterBtn, counterContainer);
}

increaseBtn.onclick = function(){
    count++;
    countLabel.textContent = count;
}
decreaseBtn.onclick = function(){
    count--;
    countLabel.textContent = count;
}
resetBtn.onclick = function(){
    count = 0;
    countLabel.textContent = count;
}

function hideDivBtn(counterBtn, counterContainer){
    if(counterContainer.style.display == "none"){
        counterContainer.style.display = "block";
        counterBtn.style.backgroundColor = "grey";
    }
    else{
        counterContainer.style.display = "none";
        counterBtn.style.backgroundColor = "rgb(4, 41, 248)";
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const divBtn = document.getElementById("divBtn");
    const hiddenDiv = document.getElementById("hiddenDiv");
    const changeBtn = document.getElementById("changeBtn");
    const label = document.getElementById("label");
    
    divBtn.onclick = function(){
        if(hiddenDiv.style.display == "none"){
            hiddenDiv.style.display = "block";
            divBtn.style.width = "300px";
        }
        else{
            hiddenDiv.style.display = "none";
            divBtn.style.width = "10%";
        }
    };

    changeBtn.addEventListener("click", function(event) {
        event.stopPropagation();
        label.textContent = "changed";
    });
});
