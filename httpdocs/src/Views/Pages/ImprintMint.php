<?php include_once './src/Views/Header.php'; ?>
<main class="main" style="padding: 20px; width: auto;background-image:none;font-size:1em;">

	<a href="/index.php">
		<?php echo _('back'); ?>
	</a>

	<div id="title" style="width: 80%">
		<h1><?php  echo _('Imprint');?></h1>
		MINTvernetzt ist ein Verbundprojekt des / der:

		<ul>
			<li>Körber-Stiftung</li>
			<li>matrix gGmbH</li>
			<li>Stifterverbands für die deutsche Wissenschaft e.V.</li>
			<li>Nationalen MINT Forum e.V.</li>
			<li>Universität Regensburg</li>
		</ul>

		<p>
			Als Dienstanbieterin im Sinne des § 5 DDG, Kontaktstelle, Betreiberin und Vertragspartnerin nach diesen Bestimmungen ist die
			matrix gGmbH zur Förderung von Demokratie, Teilhabe und nachhaltiger gesellschaftlicher Entwicklung
			<br><br>
			Rittergut Haus Morp<br>
			Düsseldorfer Straße 16<br>
			40699 Erkrath
		</p>

		<p>

			<strong>Vertretungsberechtigt:</strong><br><br>

			Gregor Frankenstein-von der Beeck<br>
			Guido Lohnherr<br>
			Tel.: +49(0)211-75707-910<br>
			Fax: +49(0)211-987300<br>
			E-Mail: <a href="MAILTO:info@matrix-ggmbh.de">info@matrix-ggmbh.de</a><br>
			Umsatzsteueridentifikationsnummer: (USt. Ident-Nr) DE 329043660<br>
			Amtsgericht Wuppertal – HRB 33341<br>
			<br><br>
			berufen.
		</p>
		<p>
			Inhaltlich verantwortlich nach § 18 MStV ist Arne Klauke, erreichbar über die Dienstanbieter.
		</p>

		<p>
			Wenn Ihr generell Fragen zum Sharepic habt, meldet Euch gerne direkt unter 
				<a href="MAILTO:sharepic@mint-vernetzt.de">
					sharepic@mint-vernetzt.de
				</a>
		</p>

</p>
	</div>
</main>

<script>
	document.getElementById('menu_sharepics').remove();
</script>

<?php include_once './src/Views/Footer.php'; ?>
