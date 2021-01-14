function move_to_other (form_id_btn) {
  const form_id = form_id_btn.substr(1);
  const form_value = document.getElementById(form_id).value;
  if (form_value === "") {
    alert("入力フォームが空白です");
    return;
  }

  let form_other_id = ["form2", "form3"];
  
  if (form_id === "form2") form_other_id[0] = "form1";
  else if (form_id === "form3") form_other_id[1] = "form1";
  
  document.getElementById(form_other_id[0]).value = form_value;
  document.getElementById(form_other_id[1]).value = form_value;
}