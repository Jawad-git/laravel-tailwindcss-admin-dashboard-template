var win = navigator.platform.indexOf("Win") > -1;
if (win && document.querySelector("#sidenav-scrollbar")) {
  var options = {
    damping: "0.5",
  };
  Scrollbar.init(document.querySelector("#sidenav-scrollbar"), options);
}

document.addEventListener("livewire:load", function () {

  window.livewire.emit("cardLoaded", false);

  Livewire.on("saved", () => {
    $("button[type=submit]").prop("disabled", true);
    $(".overlay").fadeIn();
  });
  Livewire.on("generate-token", (id) => {
    Livewire.dispatch("generateToken", { id: id });
  });
});

document.addEventListener("livewire:init", function () {
  Livewire.on("scrollToElement", () => {
    $("html, body").animate({ scrollTop: 0 }, "slow");
  });
});
function confirmDelete(id) {
  swal
  .fire({
    title: "Are you sure?",
    text: "Do you really want to delete this data?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes",
    cancelButtonText: "No",
    })
    // .fire({
    //   title: "هل انت متأكد",
    //   text: "من انك تريد حذف هذه البيانات",
    //   icon: "warning",
    //   showCancelButton: true,
    //   confirmButtonColor: "#3085d6",
    //   cancelButtonColor: "#d33",
    //   confirmButtonText: "نعم",
    //   cancelButtonText: "كلا",
    // })
    .then((result) => {
      if (result.isConfirmed) {
        console.log("tap");
        Livewire.dispatch("destroy", { id: id });
      }
    });
}

$(document).ready(function() {

  $(".selectize-2").selectize();
  });
