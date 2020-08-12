<?php

	class Parser
	{
		private $pconjList;
		private $defaultPconjList = array('WHEN','WHENEVER','WHILE','AS','AS LONG AS','AS SOON AS','SINCE','UNTIL','JUST AS','AT THE SAME TIME AS','WHERE','WHEREVER','THEREFORE','AS A RESULT','FOR THIS REASON','CONSEQUENTLY','HENCE','ACCORDINGLY','THUS','SO','BECAUSE','FOR','SINCE','IN AS MUCH AS','IN ORDER THAT','SO THAT','THAT','TO THE END THAT','FOR THE PURPOSE THAT','LEST','THUS','IN THIS MANNER','IN THAT MANNER','BY THIS MEANS','BY THAT MEANS','SUCH THAT','IF','ONLY IF','UNLESS','EXCEPT THAT','EXCEPT IF' ,'ALTHOUGH','THOUGH','EVEN THOUGH','EVEN IF','X','AND','NOW','BUT','ALSO','OR','WHETHER','TILL','THEN','NEVERTHELESS','YET','STILL','ONLY','ON THE OTHER HAND','CONVERSELY','ON THE CONTRARY','INSTEAD','NOTWITHSTANDING','NOR','LIKEWISE','EITHER','ELSE','OR ELSE','MOREOVER','NEITHER','THAN','INDEED','OTHERWISE','INASMUCH AS');

		function Parser($listOfPconj = "useDefault") { //this uses a default parameter -> it does not have to be used
												// ex. $myParser = new Parser(); OR $myParser = new Parser($myArray);
			if($listOfPconj == "useDefault") {
				$listOfPconj = $this->defaultPconjList;
			}
			
			$this->pconjList = $listOfPconj;

		}
		
		//parse unformatted text
		/*
		    - There is no format required for the parsing of unformatted text
		    
		    - The application will display the entirety of the inputted text file in a single
		      node, where the user can then choose his or her own breaks, logical or not.
		*/
		function parseUnformattedText($inputText, $bookName = "") {
			// Create book, clause, text nodes
			$book = new SimpleXmlElement("<book></book>");
			$book->addAttribute("bookName", $bookName);
			
			$conj = $book->addChild("conj", "X");
			
			$clause = $book->addChild("clause");
			
			$trimmedText = $this->trimNewLines($inputText);
			$trimmedText = $this->trimCarriageReturns($trimmedText);
			$trimmedText = $this->trimSpaces($trimmedText);
			$text = $clause->addChild("text", $trimmedText);
			$this->addChapterVerse($text, "", "");
			
			// Make list of pconj's for beginning of file
			$pconjs = $this->getPconjList();
			
			// Convert the xml to string
			$xml = $book->asXml();			
			
			// Combine the list of pconj's and xml string
			$xml = "$pconjs \n $xml";

			return $xml;
		}
		
		
		//parse formatted text
		/*
			- The format for the inputted text file is as follows:
			
			  <chapter>:<verse> <conjunction>
			  <clause>
			  <conjunction>
			  <clause>
			  ...
			  ..
	          .
			
            **********
			- An example of the above format being used in a real input file:
			
			  1:1 X
			  It seemed good to me also
			  X
			  having had perfect understanding of all things from the very first to write
			  you an orderly account, [most] excellent Theophilius
			  
			**********  
			- !!NOTE!!
			
			  ~ That <chapter> and <verse> are OPTIONAL; if omitted, then blanks: " "
			    will be inserted in their place and the colon will be left out, too
			    
			  ~ If the <chapter> and <verse> are stated once, the remaining conjunction lines will hold the same <chapter> and <verse> until the <chapter> and <verse> are specified.
			  			Ex.  1:1 X
			  				It seemed good to me also
			 				X
			 				having had perfect understanding of all things 
			  				1:2 X
			  				from the very first to write
			  				
			  				--Is the same as--
			  				
			  				1:1 X
			  				It seemed good to me also
			 			-->	1:1 X
			 				having had perfect understanding of all things 
			  				1:2 X
			  				from the very first to write
			    
			  ~ If two clauses are next to each other on separate lines, the conjunction X will be placed between the two clauses.
			  			Ex.
			  			It seemed good to me also
			 			having had perfect understanding of all things 
			  			
			  			--Is the same as--
			  			It seemed good to me also
			  			X
			 			having had perfect understanding of all things 
			  			
			    
			  ~ The conjunction X signifies that the conjunction is logically implied,
			    thus suggesting a logical break, unless it is the X at the very beginning
			    of the text, following the first <chapter> and <verse>.  This X signifies
			    the beginning of the text itself
		*/
		function parseFormattedText($inputText, $bookName = "") {
			
			// Trim the carriage return characters in the input text
			$inputText = $this->trimCarriageReturns($inputText);
			
			// Get the line of $inputText
			$inputTextArray = explode("\n", $inputText);
			
			// Create root node
			$book = new SimpleXmlElement("<book></book>");
			
			$conjunctionAvailable = False;
			$currentChapter = "";
			$currentVerse = "";
			$currentClause = "";
			
			for($i = 0; $i < count($inputTextArray); $i++)
			{
				$line = $inputTextArray[$i];
				$line = $this->trimSpaces($line);


				// Clause
				if (!($this->isLineNewChapterVerse($line)) && (!($this->isLineConjunction($line))))
				{
					$currentClause = $book->addChild("clause");
					//$currentClause->addChild("conj", "X");

					// Add text to the current clause
					$text = $currentClause->addChild("text", $line);
					// Set chapter and verse to the current chapter and verse
					$this->addChapterVerse($text, $currentChapter, $currentVerse);
				}

				// Conjunctions
				else if ($this->isLineConjunction($line))
				{
					// Add <conj> and </conj> tags into new clause
					$currentClause = $book->addChild("clause");
					
					// Add conj to current clause
					$currentClause->addChild("conj", $line);
				}

				// Chapter:Verse (1:1) or Chapter:Verse Conj (1:1 X)
				else
				{
					// Store chapter, verse, and conj into variables
					$chapterVerseConj = $this->getChapterVerseConj($line);
					$chapter = $chapterVerseConj['chapter'];
					$verse = $chapterVerseConj['verse'];
					$conj = $chapterVerseConj['conj'];
					
					// Set the chapter and verse to the current chapter and verse
					$currentChapter = $chapter;
					$currentVerse = $verse;
					
					// Create a new clause for the conj
					$currentClause = $book->addChild("clause");
					
					// Add conj to current clause
					$currentClause->addChild("conj", $conj);

					$text = $currentClause->addChild("text", " ");
					$this->addChapterVerse($text, $currentChapter, $currentVerse);
				}			
			}
			
			// After looping through the file, get the xml string
			$xml = $book->asXml();
			$pconjs = $this->getPconjList();
			
			return "$pconjs\n$xml";
			
		}
		
		
		//Parses the line passed to it to determine if it is a solitare conjunction; returns T or F
		function isLineConjunction($line) {
		
			return(array_search(strtolower($line), array_map('strtolower', $this->pconjList)) !== False);
		
		}
		
		//Parses the line passed to it to determine if it is the start of a new chapter or verse
		function isLineNewChapterVerse($line) {
		
			$chapterVerseConj = $this->getChapterVerseConj($line);
			$chapterCheck = $this->validChapterVerse($chapterVerseConj['chapter']);
			$verseCheck = $this->validChapterVerse($chapterVerseConj['verse']);
			$conjCheck = $this->isLineConjunction($chapterVerseConj['conj']);
			
			return ($chapterCheck && $verseCheck && $conjCheck);
			
		}
		
		//checks if chapter of verse is valid
		function validChapterVerse($chapterOrVerse) {
			if(is_numeric($chapterOrVerse)) {
				if ((int)$chapterOrVerse >= 0 && (int)$chapterOrVerse < 1000) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		
		//This function splits the given line into 3 variables: $chapter, $verse, $conj
		//Returns an associative array for chapter, verse, and conj
		function getChapterVerseConj($line)
		{
			$colonPos = strpos($line, ":");
			if ($colonPos !== false)
			{				
				/*
					Split the inputted $line into an array using the space character [\s]
					as the delimiter, giving you the chapter and verse in position 0 and
					the conjunction in positions 1 - end (if the conjunction is a
					multi-word conjunction.
					
					Then, create a new sub array to incorporate multi-word arrays; this
					sub array is created from $lineArray starting at 1 since position 0
					contains chapter:verse.
					
					After creating this sub array consisting solely of parts of the
					conjunction, implode it to a single string.
				*/
				
				$lineArray = preg_split("[\s]", $line);	// returns "1:1", "X"

				/*
					Explode the string at lineArray position 0 (which holds the
					chapter/verse, using the colon character as the delimiter; store the
					array result in $chapterVerse, which gives you the chapter at position
					0 and the verse at position 1
				*/

				$chapterVerse = $lineArray[0]; // returns "1:1"
				$chapterVerse = explode(":", $chapterVerse); // returns "1", "1"
				$chapter = $chapterVerse[0];
				$verse = $chapterVerse[1];

				/*
					Slice the remaining text from the line (which hold the conjunctions in
					an array) putting it into a new array in the process. Then implode this
					new array putting it back together (in case there is more than one
					conjunction on this line).
				*/

				$conjArray = array_slice($lineArray, 1);
				$conj = implode(" ", $conjArray); // combine each word of the conjunction into one string separated by a space

				if (!isset($conj) || $conj == null)
				{
					$conj = "X";
				}

				return array("chapter"=>$chapter, // returns "1"
							"verse"=>$verse, // returns "1"
							"conj"=>$conj); // returns "X" or other conjunction(s)
			}

			else
			{
				return false; //colon did not exist
			}
		
		}
		
		//adds chapter and verse to the simpleXmlElement
		function addChapterVerse($node, $chapter, $verse)
		{
			$node->addAttribute("chapter", $chapter);
			$node->addAttribute("verse", $verse);
		}
		
		//make the list of pconj's for the beginning of file
		//returns a string of pconj's in xml format
		function getPconjList()
		{
			$pconjXml = '<pconj>';
			$xmlInside = implode("</pconj>\n<pconj>", $this->pconjList);
			$pconjXml = "$pconjXml$xmlInside</pconj>\n";
			return $pconjXml;	
		}
		
		//removes all new line characters [\n]
		function trimNewLines($text)
		{
			$textOut = str_replace("\r", "\n", $text);
			$textOut = str_replace("\n", " ", $text);
			return $textOut;	
		}
		
		//remove all \r carriage returns
		function trimCarriageReturns($text)
		{
			$textOut = str_replace("\r", "\n", $text);
			$textOut = str_replace("\n\n", "\n", $textOut);
			return $textOut;
		}
		
		//trim multiple consecutive spaces down to 1
		function trimSpaces($text)
		{
			$position = strpos($text, "  ");
			while($position !== false)
			{
				$text = str_replace("  ", " ", $text);
				$position = strpos($text, "  ");
			}
			
			return $text;
		}
	}
?>