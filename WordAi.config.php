<?php
/**
 *  WordAi module config
 *
 *  @author Ivan Milincic <kreativan@outlook.com>
 *  @copyright 2018 Kreativan
 *
 *
*/

class WordAiConfig extends ModuleConfig {

	public function getInputfields() {
		$inputfields = parent::getInputfields();


		// create templates options array
		$templatesArr = array();
		foreach($this->templates as $tmp) {
			$templatesArr["$tmp"] = $tmp->name;
		}

		// create pages options array
		$pagesArr = array();
		foreach($this->pages->get("/")->children("include=hidden") as $p) {
			$pagesArr["$p"] = $p->title;
		}

        $wrapper = new InputfieldWrapper();

        // User
        // ========================================================================

        $u = $this->wire('modules')->get("InputfieldFieldset");
        $u->label = __("User");
        //$options->collapsed = 1;
        $u->icon = "fa-user";
        $wrapper->add($u);

            // email
            $f = $this->wire('modules')->get("InputfieldEmail");
            $f->attr('name', 'email');
            $f->label = 'Email ';
            $f->required = true;
            $f->columnWidth = "50%";
            $f->notes = _("Your login email. Used to authenticate on [wordai.com](http://wordai.com)");
            $u->add($f);

            // pass
            $f = $this->wire('modules')->get("InputfieldText");
            $f->attr('name', 'pass');
            $f->label = 'Password ';
            $f->required = false;
            $f->columnWidth = "50%";
            $f->notes = _("Your password. You must either use this OR hash (see below)");
            $u->add($f);

            // hash
            $f = $this->wire('modules')->get("InputfieldText");
            $f->attr('name', 'hash');
            $f->label = 'Hash ';
            $f->required = false;
            $f->columnWidth = "100%";
            $f->notes = _("It is a more secure way to send your password if you don't want to use your password.");
            $u->add($f);

        // render fieldset
        $inputfields->add($u);


        // Options
        // ========================================================================

        $options = $this->wire('modules')->get("InputfieldFieldset");
        $options->label = __("Options");
        //$options->collapsed = 1;
        $options->icon = "fa-cog";
        $wrapper->add($options);

        // Quality
        $f = $this->wire('modules')->get("InputfieldSelect");
        $f->attr('name', 'quality');
        $f->label = 'Quality ';
        $f->options = array(
            'Regular' => "Regular",
            'Unique' => "Unique",
            'Very Unique' => 'Very Unique',
            'Readable' => 'Readable',
            'Very Readable' => 'Very Readable',
        );
        $f->required = true;
        $f->defaultValue = 2;
        $f->optionColumns = 1;
        $f->columnWidth = "100%";
        $f->notes = _("'Regular', 'Unique', 'Very Unique', 'Readable', or 'Very Readable' depending on how readable vs unique you want your spin to be");
        $options->add($f);

        // output
        $f = $this->wire('modules')->get("InputfieldCheckbox");
        $f->attr('name', 'output');
        $f->label = 'JSON Output';
        $f->value = "json";
        $f->columnWidth = "100%";
        $f->notes = _("Enable if you want json output. Otherwise do not set and you will get plaintext.");
        $options->add($f);

        // returnspin
        $f = $this->wire('modules')->get("InputfieldCheckbox");
        $f->attr('name', 'returnspin');
        $f->label = 'Return Spin';
        $f->value = "true";
        $f->columnWidth = "100%";
        $f->notes = _("Enable if you want to just receive a spun version of the article you provided. Otherwise it will return spintax.");
        $options->add($f);

        // nooriginal
        $f = $this->wire('modules')->get("InputfieldCheckbox");
        $f->attr('name', 'nooriginal');
        $f->label = 'No Original';
        $f->value = "on";
        $f->columnWidth = "100%";
        $f->notes = _("Enable if you do not want to include the original word in spintax (if synonyms are found). This is the same thing as creating a **Super Unique** spin.");
        $options->add($f);

        $inputfields->add($options);

        // Text Options
        // ========================================================================

        $text = $this->wire('modules')->get("InputfieldFieldset");
        $text->label = __("Text Options");
        //$options->collapsed = 1;
        $text->icon = "fa-paragraph";
        $wrapper->add($text);

        // sentence
        $f = $this->wire('modules')->get("InputfieldCheckbox");
        $f->attr('name', 'sentence');
        $f->label = 'Sentence';
        $f->value = "on";
        $f->columnWidth = "100%";
        $f->notes = _("Enable if you want paragraph editing, where WordAi will add, remove, or switch around the order of sentences in a paragraph (recommended!)");
        $text->add($f);

        // paragraph
        $f = $this->wire('modules')->get("InputfieldCheckbox");
        $f->attr('name', 'paragraph');
        $f->label = 'Paragraph';
        $f->value = "on";
        $f->columnWidth = "100%";
        $f->notes = _("Enable if you want WordAi to do paragraph spinning - perfect for if you plan on using the same spintax many times");
        $text->add($f);

        // title
        $f = $this->wire('modules')->get("InputfieldCheckbox");
        $f->attr('name', 'title');
        $f->label = 'Title';
        $f->value = "on";
        $f->columnWidth = "100%";
        $f->notes = _("Enable if you want WordAi to automatically spin your title if there is one or add one if there isn't one");
        $text->add($f);

        // synonyms
        $f = $this->wire('modules')->get("InputfieldTextarea");
        $f->attr('name', 'synonyms');
        $f->label = 'Custom Synonyms';
        $f->rows = "7";
        $f->columnWidth = "100%";
        $f->notes = _("Add your own synonyms (Syntax: `word1|synonym1,word two|first synonym 2|2nd syn`). (comma separate the synonym sets and | separate the individuals synonyms)
*Note: The number of custom synonyms groups passed as parameter combined with the number saved in the account setting should not exceed 10,000. If it is more than 10,000, we will only take first 10,000 entries.");
        $text->add($f);


        $inputfields->add($text);


		// render fields
		return $inputfields;

	}

}
