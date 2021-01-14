function padclick (num_id) {
  const inputed_value = document.getElementById("inputed_value").innerHTML;
  const clicked_value = num_id.substr(1);
  
  if (clicked_value === "del") {
    document.getElementById("inputed_value").innerHTML = inputed_value.slice(0, -1);
  } else {
    document.getElementById("inputed_value").innerHTML = inputed_value + clicked_value;
  }
}

function decide () {
  const inputed_value = document.getElementById("inputed_value").innerHTML;
  confirm(`電話番号は、「${inputed_value}」でよろしいですか?`);
}

function padclick2 (num_id) {
  const inputed_value = document.getElementById("calculated_value").innerHTML;
  const clicked_value = num_id.substr(2);
  
  if (clicked_value === "del") {
    document.getElementById("calculated_value").innerHTML = inputed_value.slice(0, -1);
  } else {
    if (clicked_value === "times") document.getElementById("calculated_value").innerHTML = inputed_value + "×";
    else if (clicked_value === "/") document.getElementById("calculated_value").innerHTML = inputed_value + "÷";
    else document.getElementById("calculated_value").innerHTML = inputed_value + clicked_value;
  }
}

function cal() {
  const inputed_value = document.getElementById("calculated_value").innerHTML.split("");
  
  let cal_string0 = document.getElementById("calculated_value").innerHTML.replace("×", "*");
  let cal_string = cal_string0.replace("÷", "/");
  try {
    const result = Function('return ('+cal_string+');')();
    document.getElementById("result_value").innerHTML = result;
    console.log(result)
    
  } catch (error) {
    document.getElementById("result_value").innerHTML = result;
    console.log(error)
    
  }
}