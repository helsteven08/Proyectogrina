/*!
* Plugin: jQuery AJAX-ZOOM, jquery.axZm.hotspotEditor.js
* Copyright: Copyright (c) 2010-2016 Vadim Jacobi
* License Agreement: http://www.ajax-zoom.com/index.php?cid=download
* Extension Version: 2.0
* Extension Date: 2016-06-17
* URL: http://www.ajax-zoom.com
* Documentation: http://www.ajax-zoom.com/index.php?cid=docs
* Example: http://www.ajax-zoom.com/examples/example33.php
*/

eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('(11(a){3U.5c.1M||(3U.5c.1M=11(a,b){14 e=1e.1k>>>0,d=9v(b)||0,d=0>d?3h.9w(d):3h.9x(d);2r(0>d&&(d+=e);d<e;d++)17(d 3V 1e&&1e[d]===a)18 d;18-1});14 S=11(){14 a,b,e,d;a=1W.5d.5e;a=a.3W();b=/(3X)[\\/]([\\w.]+)/.1q(a)||/(3i)[ \\/]([\\w.]+)/.1q(a)||/(3j)[ \\/]([\\w.]+)/.1q(a)||/(3Y)[ \\/]([\\w.]+).*(3Z)[ \\/]([\\w.]+)/.1q(a)||/(5f)[ \\/]([\\w.]+)/.1q(a)||/(41)(?:.*3Y|)[ \\/]([\\w.]+)/.1q(a)||/(2c) ([\\w.]+)/.1q(a)||0<=a.1M("9y")&&/(42)(?::| )([\\w.]+)/.1q(a)||0>a.1M("9z")&&/(43)(?:.*? 42:([\\w.]+)|)/.1q(a)||[];e=/(5g)/.1q(a)||/(5h)/.1q(a)||/(2K)/.1q(a)||/(5i 5j)/.1q(a)||/(5k)/.1q(a)||/(5l)/.1q(a)||/(5m)/.1q(a)||/(5n)/i.1q(a)||[];a=b[3]||b[1]||"";b=b[2]||"0";e=e[0]||"";d={};a&&(d[a]=!0,d.3Y=b,d.5o=1f(b));e&&(d[e]=!0);17(d.2K||d.5g||d.5h||d["5i 5j"])d.9A=!0;17(d.5n||d.5l||d.5m||d.5k)d.9B=!0;17(d.3j||d.3X||d.3Z)d.5f=!0;17(d.42||d.3i)a="2c",d.2c=!0;d.3i&&(d.3i=!0);d.3X&&(a="41",d.41=!0);d.3Z&&d.2K&&(a="2K",d.2K=!0);d.2s=a;d.9C=e;d.2c&&-1!=5d.5e.1M("9D/5.0")&&(d.5o=9);d.43&&d.2c&&2L d.43;d.3j&&d.2c&&2L d.3j;18 d}(),A="",m=-1,T=11(a){17("1N"==1r 1W.5p)2d{9E&&t(a)&&(a=9F.9G(a)),5p.9H(a)}2e(b){}},H=11(a){14 b=1f(a);18 44(b)?!1:a==b&&a.2f()==b.2f()},U=11(c,b){17(c==b)18 0;2r(14 e=a.5q(c.1X("."),11(a){18 1f(a,10)}),d=a.5q(b.1X("."),11(a){18 1f(a,10)}),f=3h.9I(e.1k,d.1k),g=0;g<f;g++)17(e[g]=e[g]||0,d[g]=d[g]||0,e[g]!=d[g])18 e[g]>d[g]?1:-1;18 0},B=11(a,b){18"2t"==1r b?b.2M(b.9J(a)+1):b},I=11(a,b){17("2t"==1r b){14 e=B(a,b).1k;18 b.2M(0,b.1k-e-1)}18 b},V=11(c){14 b=0;t(c)&&a.1m(c,11(a){b++});18 b},t=11(a){18"1N"===1r a&&!(a 9K 3U)&&1i!==a},da=11(c){c&&a.2N(5r)&&(c=5r(c,{5s:2,5t:" ",9L:!1,9M:"9N",9O:"a 9P 9Q b i u".1X(" ")}),c=c.1d(/1O: /,"1O:"),c=c.1d(/\\( \\\'/,"(\'"),(c=c.1d(/\\\' \\)\\\'/,"\')"))&&(c=c.1d(/&#34;/g,\'"\')));18 c},W=11(c){c=a("#"+c).1G("1H");18 t(c)&&"47"==u(c.1B)?c.1B:t(c)&&"47"==u(c.5u)?c.5u:0},n=11(c){18 a("#"+(c||"2u")+" 1H:1B").j()},u=11(a){18"1N"==1r a?1i===a?"1i":a.2v==[].2v?"2O":a.2v==(9R 9S).2v?"9T":a.2v==9U().2v?"9V":"1N":1r a},p=11(c,b,e){c=c||"2u";b=b||"2P";17(a.o&&a.o.12&&V(a.o.12)){a(\'5v:eq(0)>48>a[1O="#1n-2"]\',a("#1n")).1K().1a("1D","9W-9X");a(".5w").1a("1D","49");14 d=n(c);a("#"+c+" 1H").1I();a("#"+b+" 1H").1I();14 f=a("<1s />");a.o.12&&a.1m(a.o.12,11(e){14 h=a("<1H />");e==d&&h.1b("1B",!0);a("#"+c+", #"+b).1S(h.j(e).1x(e));a("<1s />").1x("\\t&9Y; "+e).1P("9Z").1j("1t",11(){C("1E",\'"\'+e+\'":\')}).1Y(f)});a("#4a").1y().1S(f);a.1c.o.2w(1i,1i,r);x(e);D();4b 0!==d&&a("#4c").j("2Q "+d+" 2g")}1w a("#4a").1y(),a("#"+c+" 1H").1I(),a("#"+b+" 1H").1I(),a("#4c").j("2Q 2g"),2==W("1n")&&a("#1n").1G("1J","#1n-1"),a(".5w").1a("1D","2R"),a(\'5v:eq(0)>48>a[1O="#1n-2"]\',a("#1n")).1K().1a("1D","2R")},J=11(){a("#2x").1I()},r=11(){0==a("#2x").1k&&a("#1n 48:eq(3)").a0(a("<5x>").1b({2y:a.o.1T+"a1.1Z",20:"2x",a2:"1u",5y:"5z!\\5A 2g 1Q 2S 2T 3k 4d!\\5B 1U 5C.",4e:"5z!\\5A 2g 1Q 2S 2T 3k 4d!\\5B 1U 5C."}).1j("1t",11(){a("#1n").1G("1J","#1n-5");a("#4f").1G("1J","#4f-2")}))},E=11(){14 c=n();17(c&&a.o&&a.o.12){r();a("#1E").j("");a("#2U").1a("2h","#4g");a.o.12[c].5D=a("#5E").1h("19");a.o.12[c].4h=l(c,"4i");a.o.12[c].5F=a.1l(a("#5G").j())||!1;a.o.12[c].5H=1f(a("#5I").j());a.o.12[c].5J=1f(a("#5K").j());a.o.12[c].4j=l(c,"3l");a.o.12[c].2z=l(c,"21");a.o.12[c].5L=a("#5M 1H:1B").j();a.o.12[c].5N=1f(a("#5O").j());a.o.12[c].5P=1f(a("#5Q").j());a.o.12[c].5R=1f(a("#5S").j());a.o.12[c].5T=1f(a("#5U").j());a.o.12[c].5V=1f(a("#5W").j());a.o.12[c].5X=2i(a("#5Y").j());a.o.12[c].4k=l(c,"3m")||!1;a.o.12[c].5Z=a("#60").j()||"61.1Z";a.o.12[c].a3=a("#a4 1H:1B").j();a.o.12[c].1V=l(c,"3n");a.o.12[c].1L=l(c,"22");14 b=a("#4l").j();17(b||0==b){2d{b=2j("("+b.1d(/(\\r\\n|\\n|\\r)/1v,"")+")")}2e(h){b=1f(b)}44(b)?a.o.12[c].3o=!1:a.o.12[c].3o=b}1w a.o.12[c].3o=!1;14 b=a("#62").1h("19"),e=a("#63").1h("19"),d=a("#64").1h("19");a.o.12[c].3p=e&&"2V"!=e?!0:!1;a.o.12[c].65=b&&"2V"!=b?!0:!1;a.o.12[c].66=d&&"2V"!=d?!0:!1;a.o.12[c].4m=l(c,"3q")||!1;a.o.12[c].67=a("3r[2s=68]:19").j();a.o.12[c].69=a.1l(a("#6a").j())||!1;a.o.12[c].6b=a.1l(a("#6c").j())||!1;a.o.12[c].4n=a("#6d").1h("19")?!0:!1;a.o.12[c].4o=a.1l(a("#6e").j())||!1;a.o.12[c].6f=1f(a("#6g").j());a.o.12[c].1O=l(c,"4p")||!1;a.o.12[c].6h=a("#6i").1h("19")?"6j":"";a.o.12[c].6k=a("#6l").1h("19");a.o.12[c].6m=a("#6n").1h("19");a.o.12[c].6o=2i(a("#6p").j());a.o.12[c].6q=a.1l(a("#6r").j())||"#6s";a.o.12[c].6t=a("#6u").1h("19");a.o.12[c].4q=l(c,"4r")||!1;a.o.12[c].6v=a("#4s 1H:1B").j();a.o.12[c].6w=1f(a("#6x").j());a.o.12[c].6y=1f(a("#6z").j());a.o.12[c].6A=1f(a("#6B").j());a.o.12[c].6C=a.1l(a("#6D").j())||!1;a.o.12[c].6E=2i(a("#6F").j());14 f;2d{f=2j("("+a("#6G").j().1d(/(\\r\\n|\\n|\\r)/1v,"")+")")}2e(h){f={}}a.o.12[c].4t="1N"==1r f?f:{};a.1m("6H 6I 4u 4v 6J 6K 1t".1X(" "),11(b,e){14 d=a.1l(a("#1z"+e).j()),f=1i;17(d){d=d.1d(/(\\r\\n|\\n|\\r|\\t)/1v,"");"11"!=d.4w(0,8)&&(d="11(2s, a5){"+d+"}");2d{f=2j("("+d+")")}2e(g){2k("a6 6L 11 a7 6M 17 23 a8 a9 6N ab 6O!\\ac ad ae af 6L 6P ag ; ah 1m ai\\aj 6Q ak 3s be al."),f=1i}a.2N(f)?a.o.12[c][e]=d.1d(/\\"/g,"&#34;"):a.o.12[c][e]=1i}1w a.o.12[c][e]=1i});14 g=[c];a("#6R").1h("19")&&(a("#6R").1b("19",!1),g=[],a.1m(a.o.12,11(a){g.6S(a)}));2r(f=0;f<g.1k;f++)b=g[f],a.o.12[b].1C=1f(a("#6T").j()),a.o.12[b].1A=1f(a("#6U").j()),a.o.12[b].4x=a.1l(a("#6V").j()),a.o.12[b].6W=a.1l(a("#6X").j()),a.o.12[b].2l=a.1l(a("#6Y").j()),a.o.12[b].6Z=a.1l(a("#70").j()),a.o.12[b].4y=2i(a("#71").j()),a.o.12[b].4z=2i(a("#72").j()),a.o.12[b].73=a("#74 1H:1B").j(),a.o.12[b].76=1f(a("#77").j()),a.o.12[b].78=1f(a("#79").j()),a.o.12[b].3t=1f(a("#7a").j()),a.o.12[b].7b=2i(a("#7c").j()),a.o.12[b].7d=2i(a("#7e").j()),a.o.12[b].4A=a("#7f").j(),a.o.12[b].7g=a.1l(a("#7h").j()),a.o.12[b].7i=1f(a("#7j").j()),a.o.12[b].2W=a.1l(a("#7k").j()),a.o.12[b].7l=a.1l(a("#7m").j()),a.o.12[b].4B=a.1l(a("#7n").j());a.1c.o.7o();1R(11(){a.1c.o.2w(1i,1i,r);x(!0)},1F)}},D=11(){14 c=n();17(c&&a.o&&a.o.12&&a.o.12[c]){14 b=a.7p(!0,{},a.o.12[c]),e=b.5D,d=q(b.4h,"4h",c),f=b.5F,g=b.5H,h=b.5J,k=q(b.4j,"4j",c),K=q(b.2z,"2z",c),l=b.5L,ea=b.5N,m=b.5P,p=b.65,r=b.66,t=b.67,u=b.5R,w=b.5T,x=b.5V,A=b.5X,B=b.69,C=b.6b,D=q(b.4k,"4k",c),E=b.4o,F=b.4n,y=b.4t,z=b.6f,G=b.5Z,X=b.3o,H=b.6k,I=b.6m,J=b.6o,L=b.6q,M=b.6t,N=q(b.4m,"4m",c),O=b.1C,P=b.1A,Q=b.4x,R=b.6W,S=b.2l,T=b.6Z,U=b.4y,V=b.4z,W=b.73,Y=b.76,Z=b.78,aa=b.3t,ba=b.7b,ca=b.7d,7q=b.4A,7r=b.7g,7s=b.7i,7t=b.2W,7u=b.7l,7v=b.4B,7w=q(b.4q,"4q",c),7x=b.6v,7y=b.6w,7z=b.6y,7A=b.6A,7B=b.6C,7C=b.6E;1L=q(b.1L,"1L",c);1V=q(b.1V,"1V",c);3p=b.3p;K=da(K);k&&"2t"==1r k&&(k=k.1d(/&#34;/g,\'"\'));1L=da(1L);1V&&"2t"==1r 1V&&(1V=1V.1d(/&#34;/g,\'"\'));a("#5E").1b("19",e?!0:!1);a("#4r").j(7w||"");a("#4s").j(7x||"2X");a("#6x").j(7y||0);a("#6z").j(7z||0);a("#6B").j(7A||0);a("#6D").j(7B||"");a("#6F").j(7C||1);a("#6T").j(O);a("#6U").j(P);a("#6V").j(Q||"");a("#6X").j(R||"");a("#6Y").j(S||"");a("#70").j(T||"");a("#71").j(U||0);a("#72").j(V||1);a("#74").j(W||"am");a("#77").j(Y);a("#79").j(Z);a("#7a").j(aa);a("#7c").j(ba);a("#7e").j(ca);a("#7f").j(7q);a("#7h").j(7r||"");a("#7j").j(7s);a("#7k").j(7t);a("#7m").j(7u);a("#7n").j(7v);a("#3q").j(N||"");a("#6n").1b("19",I?!0:!1);a("#6l").1b("19",H?!0:!1);a("#6d").1b("19",F?!0:!1);a("#6u").1b("19",M?!0:!1);a("#6p").j(J||.75);a("#6r").j(L||"#6s");17("1N"!=1r y||a.an(y)||!y)y={};a("#60").j(G||"61.1Z");!1===X||44(X)?a("#4l").j(""):a("#4l").j(X.2f());a("#6g").j(0<=z?z:ao);a("#6a").j(B||"");a("#6c").j(C||"");a("#4i").j(d||"");a("#5G").j(f||"");a("#5I").j(1f(g));a("#5K").j(1f(h));a("#3l").j(k||"");a("#21").1o("2Y")&&v("2z");a("#21").j(K||"");a("#3n").j(1V||"");a("#22").1o("2Y")&&v("1L");a("#22").j(1L||"");a("#5M").j(l||"ap");a("#5O").j(ea||aq);a("#5Q").j(m||ar);a("#62").1b("19",p);a("#64").1b("19",r);a("#63").1b("19",3p);a("#4p").j(q(b.1O,"1O",c)||"");a("#6i").1b("19","6j"==b.6h?!0:!1);a("#5S").j(u);a("#5U").j(w);a("#5W").j(x);a("#5Y").j(A);a("#3m").j(D||"");a("#6e").j(E||"");a("#6G").j("1N"==1r y?a.7D(y):"{}");a(\'3r:as[2s="68"]\').7E(\'[2Z="\'+t+\'"]\').1b("19",!0);a.1m("6H 6I 4u 4v 6J 6K 1t".1X(" "),11(c,d){17(b[d])17("2t"==1r b[d]&&(b[d]=2j("("+b[d].1d(/&#34;/g,\'"\').1d(/(\\r\\n|\\n|\\r)/1v,"")+")")),a.2N(b[d])){14 e=b[d].2f().1d(/(\\r\\n|\\n|\\r|\\t)/1v,"");a.2N(7F)&&(e=7F(e,{5s:1,5t:"\\t"}));a("#1z"+d).j(e)}1w a("#1z"+d).j("");1w a("#1z"+d).j("")})}},F=11(c,b,e){17(e)18 f=a.7D(c);2>at.1k&&(b="");14 d=u(c);17("2O"==d){17(0==c.1k)18"[]";14 f="["}1w{14 g=0;a.1m(c,11(){g++});17(0==g)18"{}";f="{"}g=0;a.1m(c,11(a,c){0<g&&(f+=",");f="2O"==d?f+("\\n"+b+"\\t"):f+("\\n"+b+\'\\t"\'+a+\'": \');au(u(c)){2A"2O":2B;2A"1N":f+=F(c,b+"\\t");2B;2A"av":f+=c.2f();2B;2A"47":f+=c.2f();2B;2A"1i":f+="1i";2B;2A"2t":f+=\'"\'+c.1d(/\\"/g,"&#34;")+\'"\';2B;2C:f+="aw: "+1r c}g++});f="2O"==d?f+("\\n"+b+"]"):f+("\\n"+b+"}");18 f=f.1d(/;    /g,"; ")},z=11(c){18 c?"ax"==c.4w(0,4)||"/"==c.4w(0,1)?c:a.o.1T+c:""},x=11(c){a.o&&a.o.1T&&a.o.12&&1R(11(){14 b=n(),e=a.o.1T+"7G.1Z",d=a.o.1T+"7H.1Z";a("#4c").j("2Q "+b+" 2g");a.1m(a.o.12,11(c,f){17("4C"==f.2E){14 k=a.o.12[c].4x;k?b==c?(a.15.24?a("#26"+c).1b("2y",d):a("#26"+c).1b("2y",z(k)),a("#30").1y(),a("<5x>").1b("2y",z(k)).1a({1C:f.1C,1A:f.1A,"4D":"2X"}).1Y("#30")):a.15.24&&0<=k.1M("7G.1Z")||0<=k.1M("7H.1Z")?a("#26"+c).1b("2y",e):a("#26"+c).1b("2y",z(k)):b==c?(a("#30").1y(),f.2l&&(a("<1s />").1P(f.2l).1a({1C:f.1C,1A:f.1A,"4D":"2X"}).1Y("#30"),a.15.24?a("#26"+c).1K().4E("27").1P("4F"):a("#26"+c).1K().7I("4F").1P(f.2l))):(a("#26"+c).1K().7I("4F"),f.2l&&a("#26"+c).1K().1P(f.2l))}1w"7J"==f.2E&&(b==c?(a.15.24&&a("#4G"+c).1a("2W","7K"),a("#30").1y()):a("#4G"+c).1a("2W",a.o.12[c].2W))});17(a.o.12&&a.o.12[b]){14 f=a.o.12[b];f&&!c&&a.1c.o.ay(b,{az:!0,aA:!0,aB:0==f.4y&&1F==f.4z?"1%":1i})}L();D()},aC)},M=11(c){c="aD 4H 28 4I 3u 4H aE 1U aF-aG. 7L 7M 3v 28 2Z 7N <3w>2F=</3w> aH 4J 28 aI aJ / 2F 23 3s be aK. aL aM aN aO <a 1O=\\"aP: 4b(0)\\"  aQ=\\"2m(\'#1n\').1G(\'1J\',\'#1n-8\'); 2m(\'#31\').1G(\'1J\',\'#31-2\');\\"> aR -> aS 2F</a> aT aU 6O 52 (2n.4I); "+(\'<1s 2o="3x: 3y 7O 3y 7O;"><3w 2o="3t: 3y; aV-aW: #aX">\'+c+"</3w></1s>");a("#7P").1P("3z").1x(c)},w=11(){14 c=a.1c.o.aY(a("#aZ").1h("19"),!0,a("#b0").1h("19")),c=F(c,"",a("#b1").1h("19"));a("#1E").j(c);a("#2U").1a("2h","#4g")},N=11(){14 c;2d{c=2j("("+(a("#1E").j().1d(/(\\r\\n|\\n|\\r)/1v,"")||"{}")+")")}2e(b){2k(b)}"1N"==1r c&&(a.1c.o.7o(c),a("#2U").1a("2h","#4g"));r();p();x(!0);a("#b2").1h("19")&&1R(11(){a.1c.o.2w(1i,1i,r)},7Q)},O=11(){a.1c.o.32();a("#1E").j("");r();a("#33, #35").1b("19",!0);14 c=a("#7R").j().1d(/\\s+/g,"")||"b3"+3h.b4().2f(36).2M(2),b={},e=a.1l(a("#b5").j()),d=a.1l(a("#b6").j()),f=a.1l(a("#b7").j()),g=a.1l(a("#b8").j()),h={2s:c,7S:!1,7T:a("#7U").1h("19"),37:!0,b9:a("#bb").1h("19"),1Q:{2E:a("3r[2s=bc]:19").j()}};e&&d||H(e)&&H(d)?h.7T?"4C"==h.1Q.2E?a.1m(a.o.7V,11(a,c){b[a]={1u:e,1p:d}}):f&&g?a.1m(a.o.7V,11(a,c){b[a]={1u:e,1p:d,1C:f,1A:g}}):b=!1:"4C"==h.1Q.2E?b[a.o.7W]={1u:e,1p:d}:f&&g?b[a.o.7W]={1u:e,1p:d,1C:f,1A:g}:b=!1:b=!1;17("7J"==h.1Q.2E){a("#bd").1h("19")&&(h.1Q.4n=!0);a("#7X").j()&&(h.1Q.4o=a("#7X").j());14 k;2d{k=2j("("+a("#bf").j().1d(/(\\r\\n|\\n|\\r)/1v,"")+")")}2e(K){k={}}k&&(h.1Q.4t=k)}h.7S=b;a.1c.o.bg(h);p(1i,1i,!0);a("#2u [2Z="+c+"]").4K("1B",!0);a("#2P [2Z="+c+"]").4K("1B",!0);a("#7R").j("")},C=11(c,b,e){c=a("#"+c);""==c.j()&&w();e||a("#4L").1y();14 d=c.j(),f=d.1M(b);17(-1!=f){A!=b?m=0:m++;2r(14 g=b,h=0,k=g.1k,f=[],d=d.3W(),g=g.3W();-1<(h=d.1M(g,h));)f.6S(h),h+=k;g=f.1k;0<=f[m]?f=f[m]:(m=0,f=f[0]);e&&a("#4L").1x("7Y: "+(m+1)+" / "+g);c[0].bh();S.2c?(e=c[0].bi(),e.bj(!0),e.bk("7Z",f),e.bl("7Z",b.1k),e.1J()):c[0].bm(f,f+b.1k);f&&(e=bn(c[0],f),e.1p-=1F,0>e.1p&&(e.1p=0),c.80(e.1p,1F,{bo:"bp",bq:!1}));A=b}1w A="",m=-1,e&&a("#4L").1x("7Y: 0 / 0")},v=11(c){14 b=a("#1z"+c);17(b&&b.1o("2Y")){14 e=b.j(),d=b.1K().1K();"<br>"==e&&(e="");b.1K().1I();a(\'<3A 20="1z\'+c+\'" 2o="1C: 1F%; 1A: bs"></3A>\').1Y(d).j(e);18!0}18!1},P=11(c){14 b=a("#1z"+c),e=2n.38+"/o.1a".1d("//","/"),d="bt",f={81:"82",3B:"83",bu:""};"1L"==c&&(e=2n.38+"/bv/84.o.bw.1a".1d("//","/"),d="bx by",f={81:"82",3B:"83"});b.1o("2Y")?v(c):b.2Y({1A:85,bz:e,bA:11(b){1R(11(){a("4M:eq(0)",a("#1z"+c+"bB")).86().4N("3C").1a(f).1P(d).1a({1A:bC})},0);18 b}})},Y=11(c){17(a("#39").1k)a("#39").1I();1w{14 b=a(1W).1C()-a("#1n").4O()-25;17("1u"==c&&b<a.o.bD)18!1;a("<1s />").1b("20","39").1a({3B:"bE",1u:0,4A:bF,1p:0,1C:b,1A:"1F%",2h:"#bG"}).1x(\'<4M 20="87" bH="bI" 2o="1A: 1F%; 1C: 1F%; bJ-2X: #bK bL bM;" bN="0" bO="0"></4M>\').1Y("3C");1R(11(){14 b=a("#87").86();b.4N("bP").1x(a("#bQ").2G());b.4N("3C").1x(a("#4P").2G())},0);a("<1s />").1P("88").1x("2p").1j("1t",11(){a("#39").1I()}).1Y("#39").1a("2X",-a(".88").4O()-1).1a("1p",8)}},Z=11(c,b,e,d,f,g){14 h=a("<1J />");e&&h.1b("20",e);f&&h.1a(f);g&&h.1b("bR",g);a(c).1m(11(){14 c=a("<1H />").1b("2Z",1e.j).3a(1e.4Q);1e.j==b&&c.1b("1B","1B");h.1S(c)});d&&h.1o("3b",d);18 h},L=11(){a.15.29&&1<a.15.29.1k&&a.1m(G,11(c,b){"21"==b&&v("2z");"22"==b&&v("1L");a("#"+b+"2H").j("2C").1o("3b","2C")})},ba=11(c){14 b=1i;a.1m(aa,11(e,d){17(-1!=a.3E(c,d))18 b=d,!1});18 b},Q=11(c,b){14 e=a("#"+c+"2H"),d=n();17(d&&e.1k){14 f=e.j();17(f){14 g=!1;"21"==c&&(g=v("2z"));"22"==c&&(g=v("1L"));14 h=a("#"+c).j();a.15.1g[d]||(a.15.1g[d]={});a.15.1g[d][c]||(a.15.1g[d][c]={});a.15.1g[d][c][e.1o("3b")]=h;e.1o("3b",f);(e=a.15.1g[d][c][f])?a("#"+c).j(e):a("#"+c).j("");g&&P(c.1X("1z")[1]);b||(g=ba(c),a.bS(g)&&a.1m(g,11(b,d){d!=c&&(a("#"+d+"2H").j(f),Q(d,!0))}))}}},ca=11(c,b){14 e=a("#"+b+"2H");c||(c=n());17(c&&e.1k&&e.j()){14 d=a("#"+b).j();a.15.1g[c]||(a.15.1g[c]={});a.15.1g[c][b]||(a.15.1g[c][b]={});a.15.1g[c][b][e.1o("3b")]=d}},q=11(c,b,e){18 t(c)?(a.15.1g||(a.15.1g={}),a.15.1g[e]||(a.15.1g[e]={}),a.15.1g[e]["1z"+b]||(a.15.1g[e]["1z"+b]={}),a.15.1g[e]["1z"+b]=c,c=a("#1z"+b+"2H").j(),a.15.1g[e]["1z"+b][c]?a.15.1g[e]["1z"+b][c]:""):c},l=11(c,b){17(-1==a.3E(b,G))18 T(\'"\'+b+\'" 4H 2T a bT bU!\'),!1;17(a.15.1g&&a.15.1g[c]&&a.15.1g[c][b]){ca(c,b);14 e=a.7p(!0,{},a.15.1g[c][b]);a.1m(a.15.1g[c][b],11(c,d){(d=a.1l(d))?-1!=a.3E(b,R)?e[c]=d.1d(/(\\r\\n|\\n|\\r)/1v,"").1d(/\\"/g,"&#34;"):e[c]=d.1d(/\\"/g,"&#34;"):2L e[c]});18 e}14 d=a("#"+b).j();d||(d="");d=-1!=a.3E(b,R)?d.1d(/(\\r\\n|\\n|\\r)/1v,"").1d(/\\"/g,"&#34;"):d.1d(/\\"/g,"&#34;");18 a.1l(d)},aa=[["3l","21","3q"],["3n","22"]],R=["21","3m","22"],G="3l 21 3q 4r 4i 3m 4p 3n 22".1X(" ");a.15={bV:11(a){P(a)},bW:11(a){Y(a)},bX:11(a,b){18 B(a,b)},bY:11(a,b){18 I(a,b)},bZ:11(){17("2V"!==1r 2n)17(a.o.c0)2k("2I c1...");1w{a("#7P").1y().4E("2o").4E("27");14 c="",b=a("#4R").j().1d(/(\\"|\\\')/1v,""),e=a("#4S").j().1d(/(\\"|\\\')/1v,""),d=a("#89").j();17(b&&e)2k("7L 2S 1U c2 c3 1U 4T 2D 4U 8a/3D");1w 17(!b&&!e)2k("2I c4 28 8b 2r 2D 4U 8b 2r 8a/3D");1w 17(b||e){17(a("#2x").1k&&!c5("8c 8d 8e 23 2S 3k 8f 4V.\\r\\8g 23 4T 4V c6 4U c7 8h 8i,\\r\\c8 8j 3s be 8k!\\r\\n\\r\\c9 23 8l 8m 1U cb?"))18!1;a.1c.o.cc();14 f=2n.8n;f.cd=11(){a.o.ce=!1;a.1c.o.cf(d,!1,11(){a.15.1g={};L();14 b=a.1c.o.8o(!0),b=I(".",B("/",b));a("#8p").j(b?b:"");"2V"!==1r p&&1R(p,cg)});a("#7U").1b("19",a.o.8q?!0:!1)};(11(){c=b?b:e;14 d="3c";b&&/\\.(ch|1Z|ci(e|g|eg)|cj|ck|cl|cm)((#|\\?).*)?$/i.4W(b)?d="3d":b&&(d="3e");c=c.1d(/(3e=|3d=|3c=|\\"|\\\')/1v,"");14 h="cn=1&2F=8r&"+d+"="+c;M("2F=8r&"+d+"="+c);a.o&&(2L a.o.12,a("#1E").j(""),a("#4a").1y(),a("#2x").1I(),p());a.1c.o.4T({8n:f,38:2n.38,4I:h,8s:2n.8s})})()}}},co:11(){14 c="",b=a.o.cp.1X("&");a.1m(b,11(b,d){/(2F=|3e=|3d=|3c=)/1v.4W(d)&&(c+=c?"&"+d:d,-1!=d.1M("3c=")?(a("#4S").j(d.1d(/(3c=)/1v,"")),a("#4R").j("")):/(3e=|3d=)/1v.4W(d)&&(a("#4R").j(d.1d(/(3e=|3d=)/1v,"")),a("#4S").j("")))});b=a.1c.o.8o();a("#89").j(b?b:"");M(c)},3f:11(a){18 x(a)},2a:11(){a("#2a").4X(11(c){c.8t();c=a(1e);14 b=a("#1E").j(),e=a("#cq").1h("19")?1:0,d=a("#8p").j(),f=a("#cr").j(),g=a("#cs").1h("19")?1:0,h=c.1b("8u");d&&b?(a("#2q").1y().1x("ct..."),a.cu(h,{cv:b,cw:e,cx:d,cy:f,cz:g},11(b){a("#2q").1y().1S(b)}).cA(11(b){7Q==b.2b?a("#2q").1y().1S("cB cC cD cE cF 1o 1U "+h+" (2b "+b.2b+" "+b.4Y+"). 2I cG cH cI cJ 4Z cK cL 6M 3V 1e cM!"):cN==b.2b?a("#2q").1y().1S(h+" cO 2T cP 4J 1e cQ (2b "+b.2b+" "+b.4Y+\'). 2I cR 28 38 1U 2a.cS 3V 28 8u cT 7N 28 cU cV 20 "2a".\'):a("#2q").1y().1S("cW (2b "+b.2b+" "+b.4Y+"). 2I cX.")})):a("#2q").1y().1x("2I cY 8v cZ 1Q 6N 28 d0 6P d1 d2 d3 1e d4 7M be 4d!")})},d5:E,d6:D,8w:11(a,b,e){18 F(a,b,e)},3g:11(a){18 n(a)},d7:11(a){18 u(a)},8x:11(a,b,e){p(a,b,e)},d8:w,d9:11(){a("#db").j(a.15.8w(a.1c.o.dc(a.15.3g(),a("#dd").1h("19")),"",a("#de").1h("19")))},df:O,dg:11(){a("#2q").1a("3t",7);14 c=a("#1E").j();17(a("#dh").1h("19")||!c||"{}"==c)w(),1R(11(){a("#2a").4X()},1F);1w{14 b;2d{b=2j("("+(a("#1E").j().1d(/(\\r\\n|\\n|\\r)/1v,"")||"{}")+")")}2e(e){2k(e)}"1N"==1r b&&(N(),1R(11(){J();a("#2a").4X()},1F))}},2U:N,di:11(){a.15.3F?(a.1c.o.8y(),a.15.3F=!1):(a.1c.o.dj(11(){a(".3z","#3G").1a("1D","2R");18 a("#3G")},1i,1i,!0,!0,11(){a.15.3F=!1;a(".3z","#3G").1a("1D","");a("#3G").1Y("#dk")}),a.15.3F=!0)},dl:J,8z:11(){14 c="dm 23 8l 8m 1U 2L <8A>"+n()+"</8A> 2g?";a(\'<1s 20="dn" 4e="2Q 2g"><3H 27="2J-1T 2J-1T-2k" 2o="4D:1u; 3x:0 do dp 0;"></3H>\'+c+"</1s>").3I({dq:!1,1A:dr,ds:!0,dt:{2Q:11(){a.1c.o.32();a.1c.o.8z(n());p();a(1e).3I("2p")},du:11(){a(1e).3I("2p")}}})},dv:11(c){14 b=a("#"+c).j()+"\\dw 3J 3K 3L 3M, 8B 8C 8D, 3N 3O 8E 8F 8G 8H 8I 8J et 8K 8L 8M 8N, 3N 3O 8O. 8P 8Q 8R et 8S et 8T 8U 8V et ea 8W. 8X 8Y 8Z 90, 4Z 91 92 93 94 50 3J 3K 3L 3M. 50 3J 3K 3L 3M, 8B 8C 8D, 3N 3O 8E 8F 8G 8H 8I 8J et 8K 8L 8M 8N, 3N 3O 8O. 8P 8Q 8R et 8S et 8T 8U 8V et ea 8W. 8X 8Y 8Z 90, 4Z 91 92 93 94 50 3J 3K 3L 3M.";a("#"+c).j(b)},dx:11(c){a("#1n").1G("1J","#1n-8");a("#31").1G("1J","#31-3");a.80("#3P"+c,85);a("#4P dy").1a("2h","");a("#3P"+c).1K().1a("2h","#dz")},dA:11(a,b,e){C(a,b,e)},dB:11(c){"49"==a("#51").1a("1D")?(a("#95, #51").1a("1D","2R"),a(c).1x("dC")):(a("#95, #51").1a("1D",""),a(c).1x("dD"))},96:11(){a.15.29&&1<a.15.29.1k&&a.1m(G,11(c,b){14 e=a("#"+b+"dE");e.1k&&e.1y().1S(Z(a.15.29,1i,b+"2H","2C",{1C:"1F%"},\'$.15.9a("\'+b+\'")\'))})},9a:11(a){Q(a)},dF:11(){1R(11(){a("#53").1G("1J","#53-1");a("#1n").1G("1J","#1n-1");a("#54").1G("1J","#54-2")},10)},dG:11(){a.o.8q||(1==a.15.55&&(a("#33, #35").1b("19",!0),a.1c.o.32(),a.1c.o.2w()),1==a.15.24&&(a.15.24=!0,a("#3Q, #3R").1b("19",!0),a.15.3f(!0)))}};a.1c.1h=11(c){18 0<U("1.6",a.1c.84)?a(1e).1b(c):a(1e).4K(c)};a.1c.9b=11(){14 c=1e,b=c.1o("9c"),e=c.1o("56"),d,f,g,h,k,l=a("<1s />").1P("dH dI"),m=11(a){17(a.3S){14 b=a.9d+40,c=a.3S+10;b+k>d&&(b=a.9d-40-k+16);c+h>f+g&&(c=a.3S-10-h);l.1a({1p:c,1u:b})}};e&&c.1j("dJ 4u",11(n){a(".dK").2T(l).1I();n.3S&&(l.1x(\'<1s 27="dL">\'+c.3a()+\'</1s><1s 27="dM">\'+e+\'</1s><1s 27="dN">dO: \'+b+"</1s>").1a({1D:"49"}).1Y("3C"),d=1f(a(1W).1C()),f=1f(a(1W).1A()),g=1f(a(1W).dP()),h=l.dQ(),k=l.4O(),m(n))}).1j("dR",11(a){m(a)}).1j("dS 4v",11(a){l.1I()}).1a({4B:"dT"});18 1e};a.1c.2G||(a.1c.2G=11(){14 c=a(1e);17(4b 0!==c[0].2G)18 c[0].2G;14 b=c.dU("<1s/>").1K().1x();c.dV();18 b});a(9e).dW(11(){a.15.29=[];17(a.15.57){a.15.29[0]={j:"2C",4Q:"2C"};14 c=0;a.15.57[0]&&a.1m(a.15.57,11(b,e){c++;a.15.29[c]={j:e,4Q:e}});a.15.96()}a("#1n, #31, #54, #53, #dX, #4f, #dY").1G();a(".56").7E(11(){14 b=a(1e).3a(),c=a("#3P"+b).58().3a(),b=a("#3P"+b).58().58().3a().1d("<","&dZ;");a(1e).1o("56",b).1o("9c",c).9b().1a({e0:"e1"})});a.1m(a(".3z,.e2"),11(b,c){14 d=a(c);a(\'<a 27="2J-3I-e3-2p 2J-e4-6Q" 1O="#" e5="e6" 2o="3x-1u: e7; 3x-e8: 3y;"><3H 27="2J-1T 2J-1T-e9">2p</3H></a>\').1b({4e:"2p 1e 9f",5y:"2p 1e 9f"}).1P("eb").1j("1t",11(){d.1I()}).ec(d)});a.2N(a.15.2a)&&(a.15.2a(),a("#1E").j(""),1R(11(){a(\'a[1O$="#1n-5"], .ed\').1j("1t",11(){a("#1E").j()||w()})},ee));a("#3Q, #3R").1j("1t",11(){a(1e).1h("19")?(a.15.24=!0,a("#3Q, #3R").1b("19",!0)):(a.15.24=!1,a("#3Q, #3R").1b("19",!1));a.15.3f(!0)});a("#2u").1j("3v",11(){a("#2P").j(a("#2u").j()).1b("1B",!0);a.15.3f()});a("#2P").1j("3v",11(){a("#2u").j(a("#2P").j()).1b("1B",!0);a.15.3f()});a("#ef, #eh").1j("1t",11(){a.15.8x()});a("#33, #35").1j("1t",11(){a(1e).1h("19")?(a.15.55=!0,a("#33, #35").1b("19",!0),a.1c.o.32(),a.1c.o.2w()):(a.15.55=!1,a("#33, #35").1b("19",!1),a.1c.o.32(),a.1c.o.2w(!0))});a(9e).9g("3A","59",11(b){17(9==(b.5a||b.3u)){b.8t();b=a(1e).3T(0).9h;14 c=a(1e).3T(0).9i;a(1e).j(a(1e).j().2M(0,b)+"\\t"+a(1e).j().2M(c));a(1e).3T(0).9h=a(1e).3T(0).9i=b+1}1w"1E"==a(1e).1b("20")&&a("#2U").1a("2h","7K")}).9g("3r, 3A","59",11(a){a.ei()}).1j("59.9j",11(b){17(a.o&&a.1c.o.ej())17(b=b.5a||b.3u,46==b)2m.1c.o.9k(2m.15.3g(),"ek");1w 17(45==b)2m.1c.o.9k(2m.15.3g(),"el");1w 17(9l==b||98==b||1F==b||9m==b||9n==b||9o==b||99==b|97==b){14 c=a.15.3g();17(c&&(c=a("#4G"+c),c.1o("37"))){14 d=c.3B(),f=d.1p,d=d.1u;c.1o("37").5b.em();c.1o("37").5b.en();9l==b?c.1a({1p:f-1}):98==b?c.1a({1p:f+1}):1F==b?c.1a({1u:d-1}):9m==b?c.1a({1u:d+1}):9n==b?c.1a({1p:f-1,1u:d-1}):9o==b?c.1a({1p:f-1,1u:d+1}):99==b?c.1a({1p:f+1,1u:d+1}):97==b&&c.1a({1p:f+1,1u:d-1});c.1o("37").5b.eo()}}});a(1W).4J("ep.9j",11(b){17(a("#2x").1k)18(b||1W.er).es="8c 8d 8e 23 2S 3k 8f 4V.\\r\\8g 23 eu 8h 8i, 8v 8j 3s be 8k."});a("#ev").1S(a("#4P").1a("1D",""));a("#9p").1j("1t",11(){C("1E",a("#9q").j(),1)});a("#9q").1j("ew",11(b){13==(b.5a||b.3u)&&a("#9p").ex("1t")});a("#ey").1j("1t",11(){w()});a("#ez").1j("1t",11(){a("#9r, #9s, #9t").1a("1D","2R");a.1c.o.9u(!0)});a("#eA").1j("1t",11(){a("#9r, #9s, #9t").1a("1D","");a.1c.o.9u(!0)});a("#eB").1j("1t",11(){O();a.1c.o.8y()});a(".eC").1j("1t",11(){E()});a("#4s").1j("3v",11(){E()})})})(2m);',62,907,'|||||||||||||||||||val|||||axZm|||||||||||||||||||||||||||||||||||||||function|hotspots||var|aZhSpotEd||if|return|checked|css|attr|fn|replace|this|parseInt|langVal|axZmGetPropType|null|bind|length|trim|each|aZhS_tabs|data|top|exec|typeof|div|click|left|gm|else|html|empty|hotspot_|height|selected|width|display|allHotspotsCode|100|tabs|option|remove|select|parent|expHtml|indexOf|object|href|addClass|settings|setTimeout|append|icon|to|expTitle|window|split|appendTo|png|id|hotspot_toolTipHtml|hotspot_expHtml|you|highLight||axZmHotspotImg_|class|the|langArr|saveHotspotJS|status|msie|try|catch|toString|hotspot|backgroundColor|parseFloat|eval|alert|hotspotClass|jQuery|ajaxZoom|style|close|hotspotSaveToJSresults|for|name|string|hotspotSelector|constructor|hotspotsDraggable|saveWarningImg|src|toolTipHtml|case|break|default||shape|example|outerHTML|_langSel|Please|ui|android|delete|substring|isFunction|array|hotspotSelector2|Delete|none|have|not|applyJSON|undefined|borderColor|right|cleditor|value|hotspotImgPreview|aZhS_about|showHotspotLayer|hotspotSelectorDrag||hotspotSelectorDrag2||draggable|path|hotspotsDocuPopup|text|lang|3dDir|zoomData|zoomDir|colorSelectedHotspot|getHotspotSelector|Math|edge|chrome|been|hotspot_toolTipTitle|hotspot_hotspotText|hotspot_expTitle|toolTipCloseIconOffset|expFullscreen|hotspot_toolTipAjaxUrl|input|will|padding|which|change|code|margin|5px|azMsg|textarea|position|body||inArray|shownNewHotspot|newHotspotMain|span|dialog|ipsum|dolor|sit|amet|sed|diam|hsOpt_|hotspotSelectorHL|hotspotSelectorHL2|pageY|get|Array|in|toLowerCase|opr|version|safari||opera|rv|mozilla|isNaN|||number|li|block|scrollToHotspotJSON|void|hotspotDeleteButton|saved|title|aZhS_save|CCCCCC|altTitle|hotspot_altTitle|toolTipTitle|hotspotText|hotspot_toolTipCloseIconOffset|toolTipAjaxUrl|hotspotTextFill|hotspotTextClass|hotspot_href|labelTitle|hotspot_labelTitle|hotspot_labelGravity|hotspotTextCss|mouseenter|mouseleave|substr|hotspotImage|zoomRangeMin|zoomRangeMax|zIndex|cursor|point|float|removeAttr|axZm_cssHotspot_selected|axZmHotspot_|is|parameter|on|prop|jsonSearchResults|iframe|find|outerWidth|docuTable|txt|pathToLoad2D|pathToLoad360|load|or|something|test|submit|statusText|no|Lorem|testCustomNavi||aZhS_tooltip|aZhS_hotspots|drgbl|optDescr|langugaesArray|next|keydown|keyCode|options|prototype|navigator|userAgent|webkit|ipad|iphone|windows|phone|win|mac|linux|cros|versionNumber|console|map|style_html|indent_size|indent_char|active|ul|hotspotselectparent|img|alt|Warning|nNew|nClick|save|enabled|hotspot_enabled|altTitleClass|hotspot_altTitleClass|altTitleAdjustX|hotspot_altTitleAdjustX|altTitleAdjustY|hotspot_altTitleAdjustY|toolTipGravity|hotspot_toolTipGravity|toolTipWidth|hotspot_toolTipWidth|toolTipHeight|hotspot_toolTipHeight|toolTipAdjustX|hotspot_toolTipAdjustX|toolTipAdjustY|hotspot_toolTipAdjustY|toolTipFullSizeOffset|hotspot_toolTipFullSizeOffset|toolTipOpacity|hotspot_toolTipOpacity|toolTipCloseIcon|hotspot_toolTipCloseIcon|fancy_closebox|hotspot_toolTipGravFixed|hotspot_expFullscreen|hotspot_toolTipAutoFlip|toolTipGravFixed|toolTipAutoFlip|toolTipEvent|hotspot_toolTipEvent|toolTipTitleCustomClass|hotspot_toolTipTitleCustomClass|toolTipCustomClass|hotspot_toolTipCustomClass|hotspot_hotspotTextFill|hotspot_hotspotTextClass|toolTipHideTimout|hotspot_toolTipHideTimout|hrefTarget|hotspot_hrefTarget|_blank|toolTipDraggable|hotspot_toolTipDraggable|toolTipOverlayShow|hotspot_toolTipOverlayShow|toolTipOverlayOpacity|hotspot_toolTipOverlayOpacity|toolTipOverlayColor|hotspot_toolTipOverlayColor|000000|toolTipOverlayClickClose|hotspot_toolTipOverlayClickClose|labelGravity|labelBaseOffset|hotspot_labelBaseOffset|labelOffsetX|hotspot_labelOffsetX|labelOffsetY|hotspot_labelOffsetY|labelClass|hotspot_labelClass|labelOpacity|hotspot_labelOpacity|hotspot_hotspotTextCss|mouseover|mouseout|mousedown|mouseup|JS|errors|into|line|and|all|hotspotApplyAll|push|hotspot_width|hotspot_height|hotspot_hotspotImage|hotspotImageOnHover|hotspot_hotspotImageOnHover|hotspot_hotspotClass|hotspotClassOnHover|hotspot_hotspotClassOnHover|hotspot_zoomRangeMin|hotspot_zoomRangeMax|gravity|hotspot_gravity||offsetX|hotspot_offsetX|offsetY|hotspot_offsetY|hotspot_padding|opacity|hotspot_opacity|opacityOnHover|hotspot_opacityOnHover|hotspot_zIndex|backColor|hotspot_backColor|borderWidth|hotspot_borderWidth|hotspot_borderColor|borderStyle|hotspot_borderStyle|hotspot_cursor|initHotspots|extend|fa|ga|ha|ia|ja|ka|la|ma|na|oa|pa|qa|ra|toJSON|filter|js_beautify|hotspot64_green|hotspot64_red|removeClass|rect|red|You|should|of|0px|pathToParameter|500|fieldNewHotSpotName|posObj|autoPos|newHotspotAllFrames|zoomGA|zoomID|fieldHotspotTextClass|Result|character|scrollTo|overflowY|scroll|static|jquery|350|contents|iframe_docu_contents|docuCloseButton|hotspotFileToLoad|360|Path|It|looks|like|editing|nIf|before|saving|changes|lost|really|want|opt|getHotspotJsFile|jsFileName|spinMod|hotSpotEdit|divID|preventDefault|action|your|FormatJSON|updateHotspotSelector|zoomAlertClose|deleteHotspot|strong|consetetur|sadipscing|elitr|nonumy|eirmod|tempor|invidunt|ut|labore|dolore|magna|aliquyam|erat|voluptua|At|vero|eos|accusam|justo|duo|dolores|rebum|Stet|clita|kasd|gubergren|sea|takimata|sanctus|est|axZm_zoomCustomNavi|makeLangFields||||changeLang|azSimpleToolTip|optDeflt|pageX|document|message|delegate|selectionStart|selectionEnd|azHotspotEditor|toggleHotspotFrame|104|102|103|105|jsonSearchFieldSubmit|jsonSearchField|rectDimFields|rectSettings|rectAddMessage|adjustAlertBox|Number|ceil|floor|trident|compatible|mobile|desktop|platform|Trident|testTouch|JSON|stringify|log|max|lastIndexOf|instanceof|max_char|brace_style|expand|unformatted|sub|sup|new|Date|date|RegExp|regex|list|item|darr|sToHsJsonBox|prepend|batch_alert|align|toolTipCloseIconPosition|hotspot_toolTipCloseIconPosition|hotspotParam|This|contained|put|it||one|nPlease|write|well|formated|use|after|statement|nas|linebreakes|removed|center|isEmptyObject|1E3|hover|250|120|radio|arguments|switch|boolean|TYPEOF|http|zoomToHotspot|findMiddle|spinAndZoom|zoomLevel|150|Below|passed|AJAX|ZOOM|depending|final|configuration|using|For|more|info|see|javascript|onclick|About|Code|tab|around|background|color|FFFFFF|getHotspotObj|allHotspotsCodeDefaults|allHotspotsCodeImgNames|allHotspotsCodeFormat|keepDraggable|Rand_|random|fieldRectLeft|fieldRectTop|fieldRectWidth|fieldRectHeight|autoTitle||newHotspotAltTitle|hotspotShape|fieldHotspotTextFill||fieldHotspotTextCss|createNewHotspot|focus|createTextRange|collapse|moveStart|moveEnd|setSelectionRange|getCaretCoordinates|easing|swing|queue||250px|axZmToolTipInner|font|extensions|expButton|axZmEb_Descr|axZmEb_Inner|docCSSFile|updateFrame|_parent|280|boxW|fixed|5555|FFF|scrolling|yes|border|000|1px|solid|frameborder|hspace|head|hs_docu_style|onchange|isArray|multilanguage|field|toggleWYSIWYG|displayDocu|getl|getf|changeAxZmContentPHP|spinPreloading|wait|decide|whether|enter|confirm|different|reload|nyour|nDo||proceed|spinStop|onLoad|spinReverse|loadHotspotsFromJsFile|200|gif|jp|tif|tiff|psd|bmp|zoomLoadAjax|getLoadedParameters|parToPass|jsKeepFormated|jsFilePass|jsBackUp|Saving|post|jsCode|keepFormat|fileName|password|backup|fail|An|error|occured|while|sending|make|sure|there|are|PHP|typo|file|404|was|found|server|adjust|php|attribute|form|with|Error|check|import|current|formfield|define|filename|where|js|saveHotspotTooltip|updateHotspotTooltip|realTypeOf|importJSON|importJSONcoordinates||currentHotspotPositions|getHotspotPositions|currentHotspotPositionsImgNames|currentHotspotPositionsFormat|addNewHotspot|saveJSONtoFile|jsAutoImport|newHotspotButton|zoomAlert|newHotspotParent|removeWarningNotSaved|Do|deleteDialog|7px|20px|resizable|140|modal|buttons|Cancel|setLorem|nLorem|scrollToOptDocu|tr|FAFFAD|findTextInTextArea|toggleNaviBar|activate|deactivate|_divLang|hotspotAppearance|changeNot360|axZmHoverTooltipConf|someShadow|touchstart|axZmHoverTooltip|axZmHoverTooltipConfHead|axZmHoverTooltipConfInner|axZmHoverTooltipConfBottom|Default|scrollTop|outerHeight|mousemove|touchend|help|wrap|unwrap|ready|aZhS_tooltips|aZhS_events|lt|fontStyle|italic|naviInfo|titlebar|corner|role|button|10px|bottom|closethick||infoCloseButton|prependTo|linkShowJson|2E3|hotspotUpdateList||hotspotUpdateList2|stopPropagation|isMouseOver|disable|enable|start|drag|stop|beforeunload||event|returnValue||leave|hs_docu_parent|keyup|trigger|hs_jsonImportBtn|hs_hotspotShape_point|hs_hotspotShape_rect|hs_add_new_hotspot|hs_saveHotspotTooltip'.split('|'),0,{}));

/*!
https://github.com/component/textarea-caret-position
The MIT License (MIT)
Copyright (c) 2015 Jonathan Ong me@jongleberry.com
*/
;(function(){function d(b,d,e){if(!h)throw Error("textarea-caret-position#getCaretCoordinates should only be called in a browser");if(e=e&&e.debug||!1){var a=document.querySelector("#input-textarea-caret-position-mirror-div");a&&a.parentNode.removeChild(a)}a=document.createElement("div");a.id="input-textarea-caret-position-mirror-div";document.body.appendChild(a);var c=a.style,g=window.getComputedStyle?getComputedStyle(b):b.currentStyle;c.whiteSpace="pre-wrap";"INPUT"!==b.nodeName&&(c.wordWrap="break-word");
c.position="absolute";e||(c.visibility="hidden");k.forEach(function(a){c[a]=g[a]});l?b.scrollHeight>parseInt(g.height)&&(c.overflowY="scroll"):c.overflow="hidden";a.textContent=b.value.substring(0,d);"INPUT"===b.nodeName&&(a.textContent=a.textContent.replace(/\s/g,"\u00a0"));var f=document.createElement("span");f.textContent=b.value.substring(d)||".";a.appendChild(f);b={top:f.offsetTop+parseInt(g.borderTopWidth),left:f.offsetLeft+parseInt(g.borderLeftWidth)};e?f.style.backgroundColor="#aaa":document.body.removeChild(a);
return b}var k="direction boxSizing width height overflowX overflowY borderTopWidth borderRightWidth borderBottomWidth borderLeftWidth borderStyle paddingTop paddingRight paddingBottom paddingLeft fontStyle fontVariant fontWeight fontStretch fontSize fontSizeAdjust lineHeight fontFamily textAlign textTransform textIndent textDecoration letterSpacing wordSpacing tabSize MozTabSize".split(" "),h="undefined"!==typeof window,l=h&&null!=window.mozInnerScreenX;"undefined"!=typeof module&&"undefined"!=typeof module.exports?
module.exports=d:h&&(window.getCaretCoordinates=d)})();
