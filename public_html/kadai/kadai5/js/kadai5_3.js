function change_bacground (div_id_btn) {
  const id = div_id_btn.substr(1);

  if (document.getElementById(id).classList.contains("box_white")) {
    document.getElementById(id).classList.remove("box_white");
    document.getElementById(div_id_btn).innerHTML = "背景を白へ";
  } else {
    document.getElementById(id).classList.add("box_white");
    document.getElementById(div_id_btn).innerHTML = "元に戻す";
  }

}

function change_border (div_id_btn) {
  const id = div_id_btn.substr(1);

  if (document.getElementById(id).classList.contains("border_reset")) {
    document.getElementById(id).classList.remove("border_reset");
    document.getElementById(div_id_btn).innerHTML = "枠線OFF";
  } else {
    document.getElementById(id).classList.add("border_reset");
    document.getElementById(div_id_btn).innerHTML = "枠線ON";
  }

}