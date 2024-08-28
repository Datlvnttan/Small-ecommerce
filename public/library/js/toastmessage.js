const notifications = document.querySelector(".notifications");

const removeToast = (toast) => {
    toast.classList.add("remove");
    setTimeout(() => toast.remove(), 500);
};
// fa-check-circle
// fa-times-circle
// fa-exclamation-circle
// fa-info-circle
const toastDetails = {
    success: {
        icon: "ti-check",
        title: "Thành công: ",
    },
    error: {
        icon: "ti-face-sad",
        title: "Lỗi: ",
    },
    warning: {
        icon: "ti-alert",
        title: "Chú ý: ",
    },
    info: {
        icon: "ti-info-alt",
        title: "Thông báo:",
    },
};
const handleCreateToast = (id, message, typeid = null, autodelete = false) => {
    if ( typeid != null && document.getElementById(typeid))
        return;
    $(".auto-delete-toast").each(function(){
        $(this).remove();
    })    
    const { icon, title } = toastDetails[id];
    const toast = document.createElement("li");
    toast.id = typeid
    toast.className = `toast-design ${id} ${autodelete ? "auto-delete-toast" : ""}`;
    toast.innerHTML = `<div class="column">
                          <i class="bx ${icon}"></i>
                          <span>${message}</span>
                        </div>
                        <a class="message-close" onclick="removeToast(this.parentElement)"><i class='ti-close'></i></a>`
    notifications.appendChild(toast);
    setTimeout(() => removeToast(toast), 5000);
};

const clearToast = (id = null)=>{
    const toasts = $(id ? "#"+id : ".toast-design");
    toasts.each(function(){
        // $(this).remove();
        removeToast(this)
    })
}
//buttons.forEach((button) => {
//    button.addEventListener("click", () => {
//        handleCreateToast(button.id);
//    });
//});

//handleCreateToast("success","Cập thật thông tin thành công");

//handleCreateToast("error");
