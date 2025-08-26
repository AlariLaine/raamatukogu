function validateEmail(email){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }
function validatePersonalCode(pc){ return /^\d{11}$/.test(pc) && /^[1-6]/.test(pc); }
document.getElementById('registerForm')?.addEventListener('submit', function(e){
  const email = this.email.value.trim();
  const pc = this.personal_code.value.trim();
  const pwd = this.password.value;
  let errors = [];
  if(!validateEmail(email)) errors.push("Vigane e-post.");
  if(!validatePersonalCode(pc)) errors.push("Vigane isikukood.");
  if(pwd.length < 8) errors.push("Parool liiga lühike.");
  if(errors.length){
    e.preventDefault();
    alert(errors.join('\n'));
  }
});
