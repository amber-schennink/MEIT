<div>
  <h3>Info deelnemer <span id="deelnemer_form_span" class="text-sm text-white">Heb je al een acount? <span onclick="switchToLogin()" class="underline underline-offset-2 cursor-pointer">Login</span></span></h3>
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
    <label>
      <p>E-mail*</p>
      <input class="mt-1 w-full" name="deelnemer_email" type="email" required/>
    </label>
    <div class="flex flex-col md:flex-row gap-4">
      <label class="flex-1">
        <p>Wachtwoord*</p>
        <input class="w-full mt-1" id="ww" name="deelnemer_wachtwoord" type="password" required/>
      </label>
      <label class="flex-1">
        <p>Bevestig wachtwoord*</p>
        <input class="w-full mt-1" id="wwb" name="deelnemer_wachtwoord-bevestiging" type="password" required/>
      </label>
    </div>
  </div>
  <div id="deelnemer_login" class="hidden flex flex-col md:flex-row gap-4 my-8">
    <label class="flex-1">
      <p>Email</p>
      <input class="w-full mt-1" type="email" name="deelnemer_email"/>
    </label>
    <label class="flex-1">
      <p>Wachtwoord</p>
      <input class="w-full mt-1" type="password" name="deelnemer_wachtwoord"/>
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
      document.getElementById('deelnemer_form_span').classList.add('hidden')
      document.getElementById('deelnemer_type').value = "login"
    }
  </script>
</div>