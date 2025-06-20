const dropDownBtn = document.getElementsByClassName("hideDivBtn");
const standardDiv = document.getElementsByClassName("standardDiv");

dropDownBtn.onclick = function(){
    dropDownBtn.textContent = "LOOOooooOOOOOooong Button";
    dropDownBtn.style.width = "20%";
    dropDownBtn.style.height = "20%";
}