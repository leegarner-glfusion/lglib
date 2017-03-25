// ** I18N

// Calendar LT language
// Author: Mihai Bazon, <mihai_bazon@yahoo.com>
// Translator: Martynas Majeris, <martynas@solmetra.lt>
// Updated: Siegfried Gutschi (M�rz 2017) <sigi AT modellbaukalender DOT info>
// Encoding: Windows-1257
// Distributed under the same terms as the calendar itself.

// For translators: please use UTF-8 if possible.  We strongly believe that
// Unicode is the answer to a real internationalized world.  Also please
// include your contact information in the header, as can be seen above.

// full day names
Calendar._DN = new Array
("Sekmadienis",
 "Pirmadienis",
 "Antradienis",
 "Tre�iadienis",
 "Ketvirtadienis",
 "Pentadienis",
 "�e�tadienis",
 "Sekmadienis");

// Please note that the following array of short day names (and the same goes
// for short month names, _SMN) isn't absolutely necessary.  We give it here
// for exemplification on how one can customize the short day names, but if
// they are simply the first N letters of the full name you can simply say:
//
//   Calendar._SDN_len = N; // short day name length
//   Calendar._SMN_len = N; // short month name length
//
// If N = 3 then this is not needed either since we assume a value of 3 if not
// present, to be compatible with translation files that were written before
// this feature.

// short day names
Calendar._SDN = new Array
("Sek",
 "Pir",
 "Ant",
 "Tre",
 "Ket",
 "Pen",
 "�e�",
 "Sek");
 
// First day of the week. "0" means display Sunday first, "1" means display
// Monday first, etc.
Calendar._FD = 0;

// full month names
Calendar._MN = new Array
("Sausis",
 "Vasaris",
 "Kovas",
 "Balandis",
 "Gegu��",
 "Bir�elis",
 "Liepa",
 "Rugpj�tis",
 "Rugs�jis",
 "Spalis",
 "Lapkritis",
 "Gruodis");
 
// short month names
Calendar._SMN = new Array
("Sau",
 "Vas",
 "Kov",
 "Bal",
 "Geg",
 "Bir",
 "Lie",
 "Rgp",
 "Rgs",
 "Spa",
 "Lap",
 "Gru");

// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Apie kalendori�";

Calendar._TT["ABOUT"] =
"DHTML Date/Time Selector\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"Naujausi� versij� rasite: http://www.dynarch.com/projects/calendar/\n" +
"Platinamas pagal GNU LGPL licencij�. Aplankykite http://gnu.org/licenses/lgpl.html" +
"\n\n" +
"Datos pasirinkimas:\n" +
"- Met� pasirinkimas: \xab, \xbb\n" +
"- M�nesio pasirinkimas: " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + "\n" +
"- Nuspauskite ir laikykite pel�s klavi�� greitesniam pasirinkimui.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Laiko pasirinkimas:\n" +
"- Spustelkite ant valand� arba minu�i� - skai�us padid�s vienetu.\n" +
"- Jei spausite kartu su Shift, skai�ius suma��s.\n" +
"- Greitam pasirinkimui spustelkite ir pajudinkite pel�.";

Calendar._TT["PREV_YEAR"] = "Ankstesni metai (laikykite, jei norite meniu)";
Calendar._TT["PREV_MONTH"] = "Ankstesnis m�nuo (laikykite, jei norite meniu)";
Calendar._TT["GO_TODAY"] = "Pasirinkti �iandien�";
Calendar._TT["NEXT_MONTH"] = "Kitas m�nuo (laikykite, jei norite meniu)";
Calendar._TT["NEXT_YEAR"] = "Kiti metai (laikykite, jei norite meniu)";
Calendar._TT["SEL_DATE"] = "Pasirinkite dat�";
Calendar._TT["DRAG_TO_MOVE"] = "Tempkite";
Calendar._TT["PART_TODAY"] = " (�iandien)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Display %s first";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "U�daryti";
Calendar._TT["TODAY"] = "�iandien";
Calendar._TT["TIME_PART"] = "Spustelkite arba tempkite jei norite pakeisti";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%Y-%m-%d";
Calendar._TT["TT_DATE_FORMAT"] = "%A, %Y-%m-%d";

Calendar._TT["WK"] = "sav";
Calendar._TT["TIME"] = "Time";
