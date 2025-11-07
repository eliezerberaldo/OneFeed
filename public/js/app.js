let trilho = document.getElementById("trilho"); 
let main = document.querySelector("main"); 

trilho.addEventListener("click", () => { 
    trilho.classList.toggle("dark"); 
    main.classList.toggle("dark");
});