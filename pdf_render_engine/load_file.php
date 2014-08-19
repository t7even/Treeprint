<?php
	$default_src = "http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=test.ged&m=d&i=24";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
	<title></title>
	<meta name="robots" content="noindex,nofollow" />
	<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
	<meta http-equiv="content-language" content="de" />
	<meta http-equiv="imagetoolbar" content="no" />

	<style type="Text/css">
		body{
			font-family:Calibri,sans-serif;
		}
		
		#header{
			width:850px;height:2%;
			margin:0 auto 10px;
		}
		
		fieldset{
			margin:10px 0 20px;
			padding:10px;
			border: 1px solid #ccc;
			border-radius:10px;-moz-border-radius:10px;-webkit-border-radius:10px;
		}
		fieldset fieldset{
			border-radius:7px;-moz-border-radius:7px;-webkit-border-radius:7px;
		}
		label{
			display:block;
		}
		fieldset.src input[type=text]{
			width:80%;
		}
		
		#frame{
			height:94%;
		}
	</style>
</head>

<body>
<div id="wrapper">
	<div id="header">
		<!--<form action="<? print $_SERVER['PHP_SELF']; ?>" method="post" id="frm" name="frm">-->
		<form action="xml2pdf_custom_value.php" method="post">
			
			<h2>Quelle</h2>
			<fieldset class="src">
				<legend>Individuelles XML ausgeben</legend>
			<label id="l-xmlsrc" for="xmlsrc">Absolute Adresse eines bestehenden XML oder der ged2xml.php mit beliebigen Attributen</label>
			<input type="radio" name="xml" value="xmlsrc_0" checked="checked"/>
			<input type="text" name="xmlsrc_0" value="<?php echo $default_src; ?>" />
			</fieldset>
			
			<fieldset class="src">
				<legend>Vordefiniertes XML ausgeben</legend>
			
				<fieldset>
					<legend>Die Schmidts (d_andreas_schmidt.ged)</legend>
					<label>Nachfahren Willi Schmidt (ID 3685)</label>
					<input type="radio" name="xml" value="xmlsrc_10" />
					<input type="text" name="xmlsrc_10" value="http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=d_andreas_schmidt.ged&m=d&i=3685" />
		
					<label>Nachfahren Adolf Schmidt (ID 3699)</label>
					<input type="radio" name="xml" value="xmlsrc_11" />
					<input type="text" name="xmlsrc_11" value="http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=d_andreas_schmidt.ged&m=d&i=3699" />
		
					<label>Nachfahren Emil Schmidt (ID 3737)</label>
					<input type="radio" name="xml" value="xmlsrc_12" />
					<input type="text" name="xmlsrc_12" value="http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=d_andreas_schmidt.ged&m=d&i=3737" />
		
					<label>Nachfahren Andreas Schmidt (ID 3758)</label>
					<input type="radio" name="xml" value="xmlsrc_13" />
					<input type="text" name="xmlsrc_13" value="http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=d_andreas_schmidt.ged&m=d&i=3758" />
				</fieldset>
				
				<fieldset>
					<legend>Die Ibenthalers (d_johann-georg_ibenthaler.ged)</legend>
					<label>Nachfahren Johann Georg Ibenthaler (ID 3944)</label>
					<input type="radio" name="xml" value="xmlsrc_20" />
					<input type="text" name="xmlsrc_20" value="http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=d_johann-georg_ibenthaler.ged&m=d&i=3944" />
				</fieldset>
				
				<fieldset>
					<legend>Dummyfile (test.ged)</legend>
					<label>Nachfahren Baltasar Bach (ID 24)</label>
					<input type="radio" name="xml" value="xmlsrc_30" />
					<input type="text" name="xmlsrc_30" value="http://dev.t7even.com/treeprint/xml_render_engine/ged2xml.php?src=test.ged&i=24" />
				</fieldset>

			</fieldset>
			
			<h2>Ausgabe</h2>
			<fieldset>
				<legend>Elemente bzw. Detailgrad</legend>
		
				<!--
				<label id="l-mode" for="m">Modus:</label>
				<input type="radio" name="m" value="a"/> Vorfahren
				<input type="radio" name="m" value="d" checked="checked"/> Nachkommen<br/>
				
				<label id="l-id" for="i">ID:</label>
				<input type="text" name="i" value="<?php echo $i; ?>"/><br/>
				-->

				<fieldset>
					<legend>Namen</legend>
					
					<label id="l-name_length" for="name_length">Länge Vornamen</label>
					<select name="name_length" size="1">
						<option>Default (Wolfgang Amadeus)</option>
						<option>Short (Wolfgang A.)</option>
						<option>Shorter (Wolfgang)</option>
						<option>Shortest (W.)</option>
					</select>
				</fieldset>
				
				<fieldset>
					<legend>Titel / Beruf</legend>
					
					<label id="l-title-title" for="title-title">Titel</label>
					<select name="title-title" size="1">
						<option>nicht anzeigen</option>
						<option>nur Titel anzeigen</option>
						<option>Titel und Ort anzeigen</option>
					</select>

					<label id="l-title-occupation" for="title-occupation">Beruf</label>
					<input type="checkbox" name="title-occupation" value="1" /> anzeigen
				</fieldset>
				
				<fieldset>
					<legend>Lebensdaten</legend>
					
					<label id="l-dates-detail" for="dates-detail">Detailgrad Daten</label>
					<select name="dates-detail" size="1">
						<option>Komplettes Datum</option>
						<option>Nur Jahr</option>
					</select>

					<label id="l-dates-birth" for="dates-birth">Geburtsdatum (bzw. Platzhalter falls nicht bekannt)</label>
					<select name="dates-birth" size="1">
						<option>immer anzeigen</option>
						<option>nur bei nicht mehr lebenden Personen</option>
						<option>auch bei lebenden Personen</option>
						<option>auch bei Personen, deren Lebensdaten unbekannt sind</option>
						<option>nie anzeigen</option>
					</select>
<!--					<input type="checkbox" name="dates-birth" value="1" checked="checked"/> anzeigen -->

					<label id="l-dates-death" for="dates-death">Sterbedatum (bzw. Platzhalter falls nicht bekannt)</label>
					<select name="dates-death" size="1">
						<option>nur bei nicht mehr lebenden Personen</option>
						<option>auch bei Personen, deren Lebensdaten unbekannt sind</option>
						<option>auch bei lebenden Personen</option>
						<option>nie anzeigen</option>
					</select>
<!--					<input type="checkbox" name="dates-death" value="1" checked="checked"/> anzeigen -->

					<label id="l-dates-age" for="dates-age">Alter (bzw. Platzhalter falls nicht bekannt)</label>
					<select name="dates-age" size="1">
						<option>nur bei nicht mehr lebenden Personen</option>
						<option>auch bei Personen, deren Lebensdaten unbekannt sind</option>
						<option>auch bei lebenden Personen</option>
						<option>nie anzeigen</option>
					</select>
<!--					<input type="checkbox" name="dates-age" value="1" checked="checked"/> anzeigen -->

				</fieldset>

			</fieldset>		

			<fieldset>
				<legend>Design</legend>

				<fieldset>
					<legend>Theme</legend>
					
					<label id="l-design-theme" for="design-theme">Theme</label>
					<select name="design-theme" size="1">
						<option>Standard (Schriftart: Helvetica; Farbe: s/w)</option>
<!--						<option>Minimal (Schriftart: Meta; Farbe: s/w)</option> -->
					</select>

					<label id="l-design-color" for="design-color">Farbausgabe</label>
					<select name="design-color" size="1">
						<option>s/w</option>
<!--						<option>farbig</option> -->
					</select>

					<fieldset>
						<legend>Rahmen</legend>
	
						<label id="l-design-border-person" for="design-border-person">Personen</label>
						Rahmen: <select name="design-border-person" size="1">
							<option>kein</option>
							<option>umlaufend</option>
							<option>oben</option>
							<option>links</option>
							<option>rechts</option>
							<option>unten</option>
						</select>
	
						Rahmenfarbe: #<input type="text" name="design-border-color-person" value="0" /> 

						<label id="l-design-border-name" for="design-border-name">Namen</label>
						Rahmen: <select name="design-border-name" size="1">
							<option>kein</option>
							<option>umlaufend</option>
							<option>oben</option>
							<option>links</option>
							<option>rechts</option>
							<option>unten</option>
						</select>
	
						Rahmenfarbe: #<input type="text" name="design-border-color-name" value="0" /> 
	
						<label id="l-design-border-date" for="design-border-date">Daten</label>
						Rahmen: <select name="design-border-date" size="1">
							<option>kein</option>
							<option>umlaufend</option>
							<option>oben</option>
							<option>links</option>
							<option>rechts</option>
							<option>unten</option>
						</select>
	
						Rahmenfarbe: #<input type="text" name="design-border-color-date" value="0" /> 
						
						<p>Graustufen (255 Stufen) - z.B.: Schwarz = "0"; Weiss = "255"; Mittelgrau = "150"<br/>
						Farben (RGB - je 255 Stufen) - z.B.: Blau = "0,0,255"; Gelb = "255,255,0"; Hellgrün = "204,255,204"</p>
					</fieldset>

					<fieldset>
						<legend>Hintergrundfarbe</legend>
	
						<label id="l-design-bg-color-person" for="design-bg-color-person">Personen</label>
						Hintergundfarbe: #<input type="text" name="design-bg-color-person" value="255" /> 
	
						<label id="l-design-bg-color-name" for="design-bg-color-name">Namen</label>
						Hintergundfarbe: #<input type="text" name="design-bg-color-name" value="255" /> 
	
						<label id="l-design-bg-color-date" for="design-bg-color-date">Daten</label>
						Hintergundfarbe: #<input type="text" name="design-bg-color-date" value="255" /> 

						<p>Graustufen (255 Stufen) - z.B.: Schwarz = "0"; Weiss = "255"; Mittelgrau = "150"<br/>
						Farben (RGB - je 255 Stufen) - z.B.: Blau = "0,0,255"; Gelb = "255,255,0"; Hellgrün = "204,255,204"</p>
					</fieldset>

					<p>Weitere Details wie z.B.:</p>
					<ul>
						<li>Schriftgrößen
							<ul>
								<li>Header</li>
								<li>Meta-Angaben</li>
								<li>Namen</li>
								<li>Titel / Lebens-/Beziehungsdaten</li>
							</ul>
						</li>
						<li>Skalierungsfaktor</li>
						<li>Linienstärken/-stile</li>
					</ul>					
				</fieldset>
				
				<fieldset>
					<legend>Bereiche</legend>
					
					<p>z.B.:</p>
					<ul>
						<li>Header ein-/ausblenden
							<ul>
								<li>Datum Generierung einblenden/definieren/mehrfach drucken</li>
								<li>Anlass einblenden/definieren/mehrfach drucken</li>
							</ul>
						</li>
						<li>Footer ein-/ausblenden
							<ul>
								<li>Logo ein-/ausblenden</li>
								<li>Metaangaben spezifizieren</li>
							</ul>
						</li>
					</ul>
				</fieldset>
		
			</fieldset>	
			<input class="button" type="submit" class="submit" name="submit" value="PDF generieren" />
		</form>	
	</div>
	
	<!--
	<div id="content">
		<iframe id="frame" src="xml2pdf.php" width="100%"></iframe>
	</div>
	-->
</div>
</body>
</html>