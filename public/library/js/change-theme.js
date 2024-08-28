const toggler = document.getElementById('theme-toggle');
toggler.addEventListener('change', function () {
    if (this.checked) {
        localStorage.setItem("dark",true)
        document.body.classList.add('dark');
        document.body.setAttribute("data-bs-theme","dark")
    } else {
        localStorage.setItem("dark",false)
        document.body.classList.remove('dark');
        document.body.setAttribute("data-bs-theme","light")
    }
});

if(localStorage.getItem("dark") == "true")
{    
    document.body.setAttribute("data-bs-theme","dark");
    document.body.classList.add('dark');
    $(toggler).prop("checked",true)
}
