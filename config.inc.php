<?
    /*
        The configuration of the website is contained in this php file.
    */

    /*
      Main information and mysql server information.
    */
    $cfg["site_name"]				= "Anubex";
    $cfg["version"]				= "1.0";
    $cfg["help_link"]				= "<IMG SRC=pix/Inform.gif>";
    $cfg["email_link"]				= "(Mail)";
    $cfg["server"]				= "localhost";
    $cfg["user"]				= "docmgr";
    $cfg["pass"]				= "docmgr";
    $cfg["db"]	  				= "documents";

    /*
      Name & description for users without profile
    */
    $ghost["name"]				= "Ghosts";
    $ghost["desc"]				= "Users without a profile";

    /*
      Required fields for user.
    */
    $user_field["name"]					= "1";
    $user_field["email"]				= "1";
    $user_field["phone"]				= "1";
    $user_field["mobile"]				= "0";
    $user_field["organisation"]				= "0";
    $user_field["position"]				= "0";

    /*
      Required fields for document.
    */
    $doc_field["desc"]				= "0";

    /*
      Auto updating of the maintainer.
    */
    $automatic_maintainer				= "0";

    /*
      Global vars for timekeeping.
    */
    $time_format["short"]				= "month/day/year";
    $time_format["long"]				= "month/day/year - hour:min:sec";

    /*
       Keyword delimiters
    */
    $keyword_cfg[]				= " ";
    $keyword_cfg[]				= "-";

    /*
       Mime-type / icon association
    */
    $icons["standard"]				= "none.gif";
    $icons["application/pdf"]			= "pdf.gif";
    $icons["application/msword"]		= "msword.gif";
    $icons["application/octet-stream"]		= "none.gif";
    $icons["text/plain"]			= "txt.gif";
    $icons["text/html"]				= "html.gif";
    $icons["image/jpeg"]			= "jpg.gif";
    $icons["image/gif"]				= "gif.gif";
    $icons["image/bmp"]				= "bmp.gif";
    $icons["application/zip"]			= "zip.gif";
    $icons["application/x-zip-compressed"]	= "zip.gif";

    /*
       Packing variables
    */
    $packfile				= "packfile.zip";

    $unpack["application/x-zip-compressed"]		= "unzip -d ##file_path## -j -o -L ##zip_path##";
    $unpack["application/zip"]				= "unzip -d ##file_path## -j -o -L ##zip_path##";

    /*
       To text converters ( Only uncomment this if you have the respective plugin installed ) 
    */
    $to_text["text/plain"]				= "cp ##file_path## ##text_file_path##";
//    $to_text["application/msword"]			= "/usr/bin/antiword  ##file_path##  > ##text_file_path##";
//    $to_text["application/pdf"]				= "/usr/bin/pdftotext  ##file_path## ##text_file_path##";

    /*
       Full text search : Punctuation to be filtered
    */
    $punctuation[]				= ".";
    $punctuation[]				= "?";
    $punctuation[]				= "!";
    $punctuation[]				= ",";
    $punctuation[]				= ";";
    $punctuation[]				= ":";
    $punctuation[]				= "\"";
    $punctuation[]				= "(";
    $punctuation[]				= ")";
    $punctuation[]				= "[";
    $punctuation[]				= "]";
    $punctuation[]				= "~";
    $punctuation[]				= "=";
    $punctuation[]				= "- ";
    $punctuation[]				= "-\n";
    $punctuation[]				= "--";
    $punctuation[]				= "\n";

    /*
       Full text search : Words to be filtered
    */
    $noisewords[]				= "a";
    $noisewords[]				= "about";
    $noisewords[]				= "after";
    $noisewords[]				= "ago";
    $noisewords[]				= "all";
    $noisewords[]				= "almost";
    $noisewords[]				= "along";
    $noisewords[]				= "also";
    $noisewords[]				= "am";
    $noisewords[]				= "an";
    $noisewords[]				= "and";
    $noisewords[]				= "answer";
    $noisewords[]				= "any";
    $noisewords[]				= "anybody";
    $noisewords[]				= "anywhere";
    $noisewords[]				= "are";
    $noisewords[]				= "aren't";
    $noisewords[]				= "around";
    $noisewords[]				= "as";
    $noisewords[]				= "ask";
    $noisewords[]				= "at";
    $noisewords[]				= "bad";
    $noisewords[]				= "be";
    $noisewords[]				= "been";
    $noisewords[]				= "before";
    $noisewords[]				= "being";
    $noisewords[]				= "best";
    $noisewords[]				= "better";
    $noisewords[]				= "between";
    $noisewords[]				= "big";
    $noisewords[]				= "but";
    $noisewords[]				= "by";
    $noisewords[]				= "can";
    $noisewords[]				= "can't";
    $noisewords[]				= "come";
    $noisewords[]				= "could";
    $noisewords[]				= "couldn't";
    $noisewords[]				= "day";
    $noisewords[]				= "did";
    $noisewords[]				= "didn't";
    $noisewords[]				= "do";
    $noisewords[]				= "does";
    $noisewords[]				= "don't";
    $noisewords[]				= "down";
    $noisewords[]				= "each";
    $noisewords[]				= "either";
    $noisewords[]				= "else";
    $noisewords[]				= "even";
    $noisewords[]				= "ever";
    $noisewords[]				= "every";
    $noisewords[]				= "everybody";
    $noisewords[]				= "everyone";
    $noisewords[]				= "far";
    $noisewords[]				= "find";
    $noisewords[]				= "for";
    $noisewords[]				= "found";
    $noisewords[]				= "from";
    $noisewords[]				= "get";
    $noisewords[]				= "go";
    $noisewords[]				= "going";
    $noisewords[]				= "gone";
    $noisewords[]				= "good";
    $noisewords[]				= "got";
    $noisewords[]				= "had";
    $noisewords[]				= "has";
    $noisewords[]				= "have";
    $noisewords[]				= "haven't";
    $noisewords[]				= "having";
    $noisewords[]				= "hasn't";
    $noisewords[]				= "he";
    $noisewords[]				= "he's";
    $noisewords[]				= "her";
    $noisewords[]				= "here";
    $noisewords[]				= "hers";
    $noisewords[]				= "him";
    $noisewords[]				= "his";
    $noisewords[]				= "home";
    $noisewords[]				= "how";
    $noisewords[]				= "href";
    $noisewords[]				= "i";
    $noisewords[]				= "i'd";
    $noisewords[]				= "i'm";
    $noisewords[]				= "i've";
    $noisewords[]				= "if";
    $noisewords[]				= "in";
    $noisewords[]				= "into";
    $noisewords[]				= "is";
    $noisewords[]				= "isn't";
    $noisewords[]				= "it";
    $noisewords[]				= "it's";
    $noisewords[]				= "its";
    $noisewords[]				= "know";
    $noisewords[]				= "large";
    $noisewords[]				= "less";
    $noisewords[]				= "like";
    $noisewords[]				= "little";
    $noisewords[]				= "looking";
    $noisewords[]				= "look";
    $noisewords[]				= "many";
    $noisewords[]				= "me";
    $noisewords[]				= "more";
    $noisewords[]				= "most";
    $noisewords[]				= "must";
    $noisewords[]				= "my";
    $noisewords[]				= "near";
    $noisewords[]				= "never";
    $noisewords[]				= "new";
    $noisewords[]				= "news";
    $noisewords[]				= "no";
    $noisewords[]				= "none";
    $noisewords[]				= "not";
    $noisewords[]				= "nothing";
    $noisewords[]				= "of";
    $noisewords[]				= "off";
    $noisewords[]				= "often";
    $noisewords[]				= "oh";
    $noisewords[]				= "old";
    $noisewords[]				= "on";
    $noisewords[]				= "once";
    $noisewords[]				= "only";
    $noisewords[]				= "or";
    $noisewords[]				= "other";
    $noisewords[]				= "our";
    $noisewords[]				= "ours";
    $noisewords[]				= "out";
    $noisewords[]				= "over";
    $noisewords[]				= "page";
    $noisewords[]				= "please";
    $noisewords[]				= "question";
    $noisewords[]				= "rather";
    $noisewords[]				= "recent";
    $noisewords[]				= "she";
    $noisewords[]				= "she's";
    $noisewords[]				= "should";
    $noisewords[]				= "sites";
    $noisewords[]				= "small";
    $noisewords[]				= "so";
    $noisewords[]				= "some";
    $noisewords[]				= "something";
    $noisewords[]				= "sometime";
    $noisewords[]				= "somewhere";
    $noisewords[]				= "than";
    $noisewords[]				= "true";
    $noisewords[]				= "thank";
    $noisewords[]				= "that";
    $noisewords[]				= "the";
    $noisewords[]				= "their";
    $noisewords[]				= "theirs";
    $noisewords[]				= "them";
    $noisewords[]				= "then";
    $noisewords[]				= "there";
    $noisewords[]				= "these";
    $noisewords[]				= "they";
    $noisewords[]				= "they've";
    $noisewords[]				= "they're";
    $noisewords[]				= "this";
    $noisewords[]				= "those";
    $noisewords[]				= "though";
    $noisewords[]				= "through";
    $noisewords[]				= "thus";
    $noisewords[]				= "time";
    $noisewords[]				= "times";
    $noisewords[]				= "to";
    $noisewords[]				= "too";
    $noisewords[]				= "under";
    $noisewords[]				= "until";
    $noisewords[]				= "untrue";
    $noisewords[]				= "up";
    $noisewords[]				= "upon";
    $noisewords[]				= "use";
    $noisewords[]				= "users";
    $noisewords[]				= "version";
    $noisewords[]				= "very";
    $noisewords[]				= "via";
    $noisewords[]				= "want";
    $noisewords[]				= "was";
    $noisewords[]				= "wasn't";
    $noisewords[]				= "we";
    $noisewords[]				= "we've";
    $noisewords[]				= "we're";
    $noisewords[]				= "web";
    $noisewords[]				= "were";
    $noisewords[]				= "what";
    $noisewords[]				= "when";
    $noisewords[]				= "where";
    $noisewords[]				= "which";
    $noisewords[]				= "who";
    $noisewords[]				= "whom";
    $noisewords[]				= "whose";
    $noisewords[]				= "why";
    $noisewords[]				= "wide";
    $noisewords[]				= "will";
    $noisewords[]				= "with";
    $noisewords[]				= "within";
    $noisewords[]				= "without";
    $noisewords[]				= "world";
    $noisewords[]				= "worse";
    $noisewords[]				= "worst";
    $noisewords[]				= "would";
    $noisewords[]				= "www";
    $noisewords[]				= "yes";
    $noisewords[]				= "yet";
    $noisewords[]				= "you";
    $noisewords[]				= "you've";
    $noisewords[]				= "you're";
    $noisewords[]				= "your";
    $noisewords[]				= "yours";
?>
