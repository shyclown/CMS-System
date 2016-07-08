<form name="compForm">
<input id="manipulate">

</form>
<script>
class txtArea{

  constructor(id)
  {
    this.area_id = id;
    this.btn_wrap;
    this.content_wrap;

    this.switch_wrap;

    this.el_editor_wrap;
    this.build_editor();
  }

  // FNS

  _(id){ return document.getElementById(id); }
  _el(tag){ return document.createElement(tag);}
  _insertBefore(dom_id, el){ dom_id.parentNode.insertBefore(el, dom_id); }

  // ELEMENTS
  build_editor(){

    this.hide_textarea();

    this.create_editor_wrap();
    this.create_buttons_wrap();
    this.generate_buttons();
    this.create_content_wrap();

    this.create_html_switch_wrap();
    this.generate_html_switch();
  }

  hide_textarea(){
    console.log(this._(this.area_id));
    this._(this.area_id).type = 'hidden';
  }
  create_editor_wrap(){
    this.el_editor_wrap = this._el('DIV');
    this._insertBefore(this._(this.area_id),this.el_editor_wrap);
  }
  create_buttons_wrap(){
    this.btn_wrap = this._el('DIV');
    this.btn_wrap.className = 'ctrl_btns';
    this.el_editor_wrap.appendChild(this.btn_wrap);
  }
  create_content_wrap(){
    this.content_wrap = this._el('DIV');
    this.content_wrap.contentEditable = true;
    this.el_editor_wrap.appendChild(this.content_wrap);
  }
  create_html_switch_wrap(){
    this.switch_wrap = this._el('DIV');
    this.switch_wrap.className = 'switch-wrap';
    this.el_editor_wrap.appendChild(this.switch_wrap);
  }

  generate_html_switch(){
    this.switch = this._el('INPUT');
    this.switch.type = "checkbox";
    this.switch.name = "switchMode";
    this.switch_wrap.appendChild(this.switch);

    this.switch_label = this._el('LABEL');
    this.switch_label.setAttribute('for','switchMode');
    this.switch_label.textContent = 'Show HTML';
    this.switch_wrap.appendChild(this.switch_label);

    // add action
    var self  = this;
    this.switch.addEventListener('change', function(){
      self.set_document_mode(this.checked);
    }, false);
  }

  format_doc(sCmd, sValue){
    if (this.validate_mode())
    {/*
      if(sValue=="CODE")
      {
        var range = document.getSelection();
        selectedElement = document.getSelection().anchorNode.parentElement;
        var selection =  selectedElement.innerHTML;
        var code = document.createElement("CODE");
        code.innerHTML = selection;
        tag = selectedElement.tagName;
        getTag = sValue.toUpperCase();

        if(tag == 'CODE' || tag =='LI' || tag == 'H3' || tag == 'H2'){} //do nothing
        else if(tag == 'P'){
          selectedElement.innerHTML = '';
          selectedElement.appendChild(code);
        }
        else{
          selectedElement.parentElement.insertBefore(code, selectedElement);
          selectedElement.parentElement.removeChild(selectedElement);
        }
      }*/
      /*else */if(sValue=="p"||sValue=="h2"||sValue=="h3")
      {
        let range = document.getSelection();
        let selectedElement = document.getSelection().anchorNode.parentElement;
        let tag = selectedElement.tagName;
        let getTag = sValue.toUpperCase();
        let id = selectedElement.parentNode.id;
        
        if(tag == getTag || tag =='LI'){} //do nothingid!='article-content'
        else
        {
          document.execCommand(sCmd, false, sValue); this.content_wrap.focus();
        }
      }
      else
      {
        document.execCommand(sCmd, false, sValue); this.content_wrap.focus();
      }
    }
  }

  remove_format(){
    this.format_doc('removeFormat');
    this.format_doc('formatblock','p');
  }
  validate_mode(){
    if (!this.switch.checked) { return true ; }
    alert("Uncheck \"Show HTML\".");
    this.content_wrap.focus();
    return false;
  }
  set_document_mode(bToSource){
    var oContent;
    var self = this;
    if (bToSource) {
      oContent = document.createTextNode(self.content_wrap.innerHTML);
      self.content_wrap.innerHTML = "";
      var oPre = document.createElement("pre");
      self.content_wrap.contentEditable = false;
      oPre.id = "sourceText";
      oPre.contentEditable = true;
      oPre.appendChild(oContent);
      self.content_wrap.appendChild(oPre);
    } else {
      if (document.all) {
        self.content_wrap.innerHTML = self.content_wrap.innerText;
      } else {
        oContent = document.createRange();
        oContent.selectNodeContents(self.content_wrap.firstChild);
        self.content_wrap.innerHTML = oContent.toString();
      }
      self.content_wrap.contentEditable = true;
    }
  }

  generate_buttons(){
    var self = this;
    var oPlacement = this.btn_wrap;

    var resource_folder = '/elephant/resources/icons-editor/';
    var images_class = 'intLink';

    var btns = {
      Undo: {
        nicename:'Undo',
        fname:'undo.png',
        btn_event: function(){self.format_doc('undo')}
      },
      Redo: {
        nicename:'Redo',
        fname:'redo.png',
        btn_event: function(){self.format_doc('redo')}
      },
      Remove_formating: {
        nicename:'Remove_formating',
        fname:'formatx.png',
        btn_event: function(){self.remove_format()}
      },
      Header_H2: {
        nicename:'Header_H2',
        fname:'h2.png',
        btn_event: function(){self.format_doc('formatblock','h2')}
      },
      Header_H3: {
        nicename:'Header_H3',
        fname:'h3.png',
        btn_event: function(){self.format_doc('formatblock','h3')}
      },
      Bold: {
        nicename:'Bold',
        fname:'bold_2.png',
        btn_event: function(){self.format_doc('bold')}
      },
      Italic: {
        nicename:'Italic',
        fname:'italic.png',
        btn_event: function(){self.format_doc('italic')}
      },
      Underline: {
        nicename:'Underline',
        fname:'underline.png',
        btn_event: function(){self.format_doc('underline')}
      },
      Align_left: {
        nicename:'Align left',
        fname:'align_left.png',
        btn_event: function(){self.format_doc('justifyleft');}
      },
      Align_center: {
        nicename:'Align center',
        fname:'align_center.png',
        btn_event: function(){self.format_doc('justifycenter');}
      },
      Align_right: {
        nicename:'Align right',
        fname:'align_right.png',
        btn_event:function(){self.format_doc('justifyright');}
      },
      Align_full: {
        nicename:'Align full',
        fname:'align_full.png',
        btn_event: function(){self.format_doc('justifyFull');}
      },
      Numbered_list: {
        nicename:'Numbered list',
        fname:'nr_list.png',
        btn_event: function(){self.format_doc('insertorderedlist');}
      },
      Dotted_list: {
        nicename:'Dotted list',
        fname:'ul_list.png',
        btn_event: function(){self.format_doc('insertunorderedlist');}
      },
      Add_identation: {
        nicename:'Add identation',
        fname:'tab_right.png',
        btn_event: function(){self.format_doc('indent');}
      },
      Delete_identation: {
        nicename:'Delete identation',
        fname:'tab_left.png',
        btn_event: function(){self.format_doc('outdent');}
      },
      Quote:{
        nicename:'Quote',
        fname:'quote.png',
        btn_event: function(){self.format_doc('formatblock','BLOCKQUOTE')}
      },
      Hyperlink:{
        nicename:'Hyperlink',
        fname:'link_article.png',
        btn_event:function(){var sLnk=prompt('Write the URL here','http:\/\/');if(sLnk&&sLnk!=''&&sLnk!='http://'){self.format_doc('createlink',sLnk)}}
      },
      Code: {
        nicename:'Code',
        fname:'code_icon.png',
        btn_event: function(){self.format_doc('formatblock','code')}
      },
      Print: {
        nicename:'Print',
        fname:'print.png',
        btn_event: function(){self.printDoc()}
      },
    }
    var populate = function()
    {
      for(var item in btns){
        var el = document.createElement('img');
        oPlacement.appendChild(el);
        el.src = resource_folder+btns[item].fname;
        el.className = images_class;
        el.title = btns[item].nicename;
        el.addEventListener('click',btns[item].btn_event,false);
      }
      document.compForm.addEventListener('submit',function(){
        //event.preventDefault();
        if(self.validateMode())
        {
          this.content.value=self.oDoc.innerHTML;
          return true;
        }
        return false;
      },false);
    }
    populate();
  }
}
new txtArea('manipulate');


</script>
