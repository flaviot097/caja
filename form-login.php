<form id="contactus" action="session.php" method="post">
    <h3>Iniciar Sesion</h3>

    <fieldset>
        <input placeholder="usuario" type="text" tabindex="1" required autofocus />
    </fieldset>
    <fieldset>
        <input placeholder="Email Address" type="email" tabindex="2" required />
    </fieldset>
    <fieldset>
        <input placeholder="Phone Number" type="tel" tabindex="3" required />
    </fieldset>
    <fieldset>
        <textarea placeholder="Type your message here..." tabindex="5" required></textarea>
    </fieldset>
    <fieldset>
        <button name="submit" type="submit" id="contactus-submit" data-submit="...Sending">
            Send Now
        </button>
    </fieldset>
</form>