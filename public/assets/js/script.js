// Contact Number / Age Validation / Pincode Validation / Aadhar Validation
function validate_contact(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.length > 10) {
        value = value.slice(0, 10);
    }
    input.value = value;
}
function validate_age(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.length > 3) {
        value = value.slice(0, 3);
    }
    input.value = value;
}
function validate_pin(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.length > 6) {
        value = value.slice(0, 6);
    }
    input.value = value;
}
function validate_aadhar(input) {
    let value = input.value.replace(/\D/g, "");
    if (value.length > 12) {
        value = value.slice(0, 12);
    }
    input.value = value;
}
