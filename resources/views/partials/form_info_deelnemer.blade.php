<div>
  <h3 id="deelnemer_form_h3_form">Info deelnemer <span class="text-sm text-white">Heb je al een acount? <span onclick="switchToLogin()" class="underline underline-offset-2 cursor-pointer">Login</span></span></h3>
  <h3 id="deelnemer_form_h3_login" class="hidden">Login <span id="deelnemer_form_span" class="text-sm text-white">Nog geen acount? <span onclick="switchToForm()" class="underline underline-offset-2 cursor-pointer">Aanmelden</span></span></h3>
  <input id="deelnemer_type" class="hidden" name="deelnemer_type" value="form" readonly/>
  <div id="deelnemer_form" class="my-8 font-semibold flex flex-col gap-4">
    <div class="flex flex-col md:flex-row gap-4 mt-1">
      <label class="flex-1">
        <p>Voornaam*</p>
        <input class="w-full mt-1" name="deelnemer_voornaam" type="text" required/>
      </label>
      <label class="md:max-w-[20%]">
        <p>Tussenvoegsel</p>
        <input class="mt-1 w-full" name="deelnemer_tussenvoegsel" type="text"/>
      </label>
      <label class="flex-1">
        <p>Achternaam*</p>
        <input class="w-full mt-1" name="deelnemer_achternaam" type="text" required/>
      </label>
    </div>
    <div class="flex flex-col md:flex-row gap-4 mt-1">
      <label class="flex-1">
        <p>Geboorte datum*</p>
        <input class="w-full mt-1" name="geboorte_datum" type="date" required/>
        <span class="text-xs text-white/70">Verplicht voor het maken van je MEIT. profiel</span>
      </label>
      <label class="flex-1">
        <p>Geboorte tijd*</p>
        <input class="w-full mt-1" name="geboorte_tijd" type="time" required/>
        <span class="text-xs text-white/70">Verplicht voor het maken van je MEIT. profiel</span>
      </label>
      <label class="flex-1">
        <p>Geboorte plaats*</p>
        <input class="w-full mt-1" name="geboorte_plaats" type="text" required/>
        <span class="text-xs text-white/70">Verplicht voor het maken van je MEIT. profiel</span>
      </label>
    </div>
    <label>
      <p>E-mail*</p>
      <input id="username" class="mt-1 w-full" name="deelnemer_email" type="email" required/>
    </label>
    <label>
      <p>Telefoonnummer*</p>
      <input class="mt-1 w-full" name="deelnemer_telefoon" type="tel" required/>
    </label>
    <div class="flex flex-col md:flex-row gap-4">
      <label class="flex-1 relative">
        <p>Wachtwoord*</p>
        <input class="w-full mt-1" id="ww" name="deelnemer_wachtwoord" type="password" required/>
        <svg onclick="this.previousElementSibling.type = 'text'; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden')" class="absolute h-5 right-2 bottom-2.5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M320 96C239.2 96 174.5 132.8 127.4 176.6C80.6 220.1 49.3 272 34.4 307.7C31.1 315.6 31.1 324.4 34.4 332.3C49.3 368 80.6 420 127.4 463.4C174.5 507.1 239.2 544 320 544C400.8 544 465.5 507.2 512.6 463.4C559.4 419.9 590.7 368 605.6 332.3C608.9 324.4 608.9 315.6 605.6 307.7C590.7 272 559.4 220 512.6 176.6C465.5 132.9 400.8 96 320 96zM176 320C176 240.5 240.5 176 320 176C399.5 176 464 240.5 464 320C464 399.5 399.5 464 320 464C240.5 464 176 399.5 176 320zM320 256C320 291.3 291.3 320 256 320C244.5 320 233.7 317 224.3 311.6C223.3 322.5 224.2 333.7 227.2 344.8C240.9 396 293.6 426.4 344.8 412.7C396 399 426.4 346.3 412.7 295.1C400.5 249.4 357.2 220.3 311.6 224.3C316.9 233.6 320 244.4 320 256z"/></svg>
        <svg onclick="this.previousElementSibling.previousElementSibling.type = 'password'; this.classList.add('hidden'); this.previousElementSibling.classList.remove('hidden')" class="hidden absolute h-5 right-2 bottom-2.5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M73 39.1C63.6 29.7 48.4 29.7 39.1 39.1C29.8 48.5 29.7 63.7 39 73.1L567 601.1C576.4 610.5 591.6 610.5 600.9 601.1C610.2 591.7 610.3 576.5 600.9 567.2L504.5 470.8C507.2 468.4 509.9 466 512.5 463.6C559.3 420.1 590.6 368.2 605.5 332.5C608.8 324.6 608.8 315.8 605.5 307.9C590.6 272.2 559.3 220.2 512.5 176.8C465.4 133.1 400.7 96.2 319.9 96.2C263.1 96.2 214.3 114.4 173.9 140.4L73 39.1zM236.5 202.7C260 185.9 288.9 176 320 176C399.5 176 464 240.5 464 320C464 351.1 454.1 379.9 437.3 403.5L402.6 368.8C415.3 347.4 419.6 321.1 412.7 295.1C399 243.9 346.3 213.5 295.1 227.2C286.5 229.5 278.4 232.9 271.1 237.2L236.4 202.5zM357.3 459.1C345.4 462.3 332.9 464 320 464C240.5 464 176 399.5 176 320C176 307.1 177.7 294.6 180.9 282.7L101.4 203.2C68.8 240 46.4 279 34.5 307.7C31.2 315.6 31.2 324.4 34.5 332.3C49.4 368 80.7 420 127.5 463.4C174.6 507.1 239.3 544 320.1 544C357.4 544 391.3 536.1 421.6 523.4L357.4 459.2z"/></svg>
      </label>
      <label class="flex-1 relative">
        <p>Bevestig wachtwoord*</p>
        <input class="w-full mt-1" id="wwb" name="deelnemer_wachtwoord-bevestiging" type="password" required/>
        <svg onclick="this.previousElementSibling.type = 'text'; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden')" class="absolute h-5 right-2 bottom-2.5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M320 96C239.2 96 174.5 132.8 127.4 176.6C80.6 220.1 49.3 272 34.4 307.7C31.1 315.6 31.1 324.4 34.4 332.3C49.3 368 80.6 420 127.4 463.4C174.5 507.1 239.2 544 320 544C400.8 544 465.5 507.2 512.6 463.4C559.4 419.9 590.7 368 605.6 332.3C608.9 324.4 608.9 315.6 605.6 307.7C590.7 272 559.4 220 512.6 176.6C465.5 132.9 400.8 96 320 96zM176 320C176 240.5 240.5 176 320 176C399.5 176 464 240.5 464 320C464 399.5 399.5 464 320 464C240.5 464 176 399.5 176 320zM320 256C320 291.3 291.3 320 256 320C244.5 320 233.7 317 224.3 311.6C223.3 322.5 224.2 333.7 227.2 344.8C240.9 396 293.6 426.4 344.8 412.7C396 399 426.4 346.3 412.7 295.1C400.5 249.4 357.2 220.3 311.6 224.3C316.9 233.6 320 244.4 320 256z"/></svg>
        <svg onclick="this.previousElementSibling.previousElementSibling.type = 'password'; this.classList.add('hidden'); this.previousElementSibling.classList.remove('hidden')" class="hidden absolute h-5 right-2 bottom-2.5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M73 39.1C63.6 29.7 48.4 29.7 39.1 39.1C29.8 48.5 29.7 63.7 39 73.1L567 601.1C576.4 610.5 591.6 610.5 600.9 601.1C610.2 591.7 610.3 576.5 600.9 567.2L504.5 470.8C507.2 468.4 509.9 466 512.5 463.6C559.3 420.1 590.6 368.2 605.5 332.5C608.8 324.6 608.8 315.8 605.5 307.9C590.6 272.2 559.3 220.2 512.5 176.8C465.4 133.1 400.7 96.2 319.9 96.2C263.1 96.2 214.3 114.4 173.9 140.4L73 39.1zM236.5 202.7C260 185.9 288.9 176 320 176C399.5 176 464 240.5 464 320C464 351.1 454.1 379.9 437.3 403.5L402.6 368.8C415.3 347.4 419.6 321.1 412.7 295.1C399 243.9 346.3 213.5 295.1 227.2C286.5 229.5 278.4 232.9 271.1 237.2L236.4 202.5zM357.3 459.1C345.4 462.3 332.9 464 320 464C240.5 464 176 399.5 176 320C176 307.1 177.7 294.6 180.9 282.7L101.4 203.2C68.8 240 46.4 279 34.5 307.7C31.2 315.6 31.2 324.4 34.5 332.3C49.4 368 80.7 420 127.5 463.4C174.6 507.1 239.3 544 320.1 544C357.4 544 391.3 536.1 421.6 523.4L357.4 459.2z"/></svg>
      </label>
    </div>
    <label class="flex gap-2 checkbox-label">
      <input class="w-6 h-6 opacity-0 absolute" type="checkbox" required/>
      <span></span>
      <p>Ik heb de <a target="_blank" href="https://www.meit.nl/privacyverklaring" class="underline underline-offset-2">Privacyverklaring</a> gelezen en begrepen.</p>
    </label>
  </div>
  <div id="deelnemer_login" class="hidden flex flex-col md:flex-row gap-4 my-8 font-semibold">
    <label class="flex-1">
      <p>Email*</p>
      <input class="w-full mt-1" type="email" name="login_email"/>
    </label>
    <label class="flex-1 relative">
      <p>Wachtwoord*</p>
      <input class="w-full mt-1" type="password" name="login_wachtwoord"/>
      <svg onclick="this.previousElementSibling.type = 'text'; this.classList.add('hidden'); this.nextElementSibling.classList.remove('hidden')" class="absolute h-5 right-2 bottom-2.5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M320 96C239.2 96 174.5 132.8 127.4 176.6C80.6 220.1 49.3 272 34.4 307.7C31.1 315.6 31.1 324.4 34.4 332.3C49.3 368 80.6 420 127.4 463.4C174.5 507.1 239.2 544 320 544C400.8 544 465.5 507.2 512.6 463.4C559.4 419.9 590.7 368 605.6 332.3C608.9 324.4 608.9 315.6 605.6 307.7C590.7 272 559.4 220 512.6 176.6C465.5 132.9 400.8 96 320 96zM176 320C176 240.5 240.5 176 320 176C399.5 176 464 240.5 464 320C464 399.5 399.5 464 320 464C240.5 464 176 399.5 176 320zM320 256C320 291.3 291.3 320 256 320C244.5 320 233.7 317 224.3 311.6C223.3 322.5 224.2 333.7 227.2 344.8C240.9 396 293.6 426.4 344.8 412.7C396 399 426.4 346.3 412.7 295.1C400.5 249.4 357.2 220.3 311.6 224.3C316.9 233.6 320 244.4 320 256z"/></svg>
      <svg onclick="this.previousElementSibling.previousElementSibling.type = 'password'; this.classList.add('hidden'); this.previousElementSibling.classList.remove('hidden')" class="hidden absolute h-5 right-2 bottom-2.5 cursor-pointer" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.1.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M73 39.1C63.6 29.7 48.4 29.7 39.1 39.1C29.8 48.5 29.7 63.7 39 73.1L567 601.1C576.4 610.5 591.6 610.5 600.9 601.1C610.2 591.7 610.3 576.5 600.9 567.2L504.5 470.8C507.2 468.4 509.9 466 512.5 463.6C559.3 420.1 590.6 368.2 605.5 332.5C608.8 324.6 608.8 315.8 605.5 307.9C590.6 272.2 559.3 220.2 512.5 176.8C465.4 133.1 400.7 96.2 319.9 96.2C263.1 96.2 214.3 114.4 173.9 140.4L73 39.1zM236.5 202.7C260 185.9 288.9 176 320 176C399.5 176 464 240.5 464 320C464 351.1 454.1 379.9 437.3 403.5L402.6 368.8C415.3 347.4 419.6 321.1 412.7 295.1C399 243.9 346.3 213.5 295.1 227.2C286.5 229.5 278.4 232.9 271.1 237.2L236.4 202.5zM357.3 459.1C345.4 462.3 332.9 464 320 464C240.5 464 176 399.5 176 320C176 307.1 177.7 294.6 180.9 282.7L101.4 203.2C68.8 240 46.4 279 34.5 307.7C31.2 315.6 31.2 324.4 34.5 332.3C49.4 368 80.7 420 127.5 463.4C174.6 507.1 239.3 544 320.1 544C357.4 544 391.3 536.1 421.6 523.4L357.4 459.2z"/></svg>
    </label>
  </div>
  <script>
    function switchToLogin(){
      form = document.getElementById('deelnemer_form')
      form.classList.add('hidden')
      inputs = [... form.getElementsByTagName('input')]
      inputs.forEach(input => {
        input.required = false;
      });
      login = document.getElementById('deelnemer_login')
      login.classList.remove('hidden')
      inputs = [... login.getElementsByTagName('input')]
      inputs.forEach(input => {
        input.required = true;
      });
      login_button = document.getElementById('login_button');
      if(login_button){
        login_button.innerHTML = 'Login';
      }
      document.getElementById('deelnemer_form_h3_form').classList.add('hidden')
      document.getElementById('deelnemer_form_h3_login').classList.remove('hidden')
      document.getElementById('deelnemer_type').value = "login"
    }
    function switchToForm(){
      form = document.getElementById('deelnemer_form')
      form.classList.remove('hidden')
      inputs = [... form.getElementsByTagName('input')]
      $notRequired = ['deelnemer_tussenvoegsel']
      inputs.forEach(input => {
        if(!$notRequired.includes(input.name)){
          input.required = true;
        }
      });
      login = document.getElementById('deelnemer_login')
      login.classList.add('hidden')
      inputs = [... login.getElementsByTagName('input')]
      inputs.forEach(input => {
        input.required = false;
      });
      login_button = document.getElementById('login_button');
      if(login_button){
        login_button.innerHTML = 'Aanmelden';
      }
      document.getElementById('deelnemer_form_h3_form').classList.remove('hidden')
      document.getElementById('deelnemer_form_h3_login').classList.add('hidden')
      document.getElementById('deelnemer_type').value = "form"
    }
  </script>
</div>