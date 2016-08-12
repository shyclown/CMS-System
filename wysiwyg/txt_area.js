class txtArea{

  constructor(form_id, input_id)
  {
    this.area_id = input_id;
    this.form_area = document.getElementById(form_id);
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
    this.content_wrap.innerHTML = this._(this.area_id).value;
    this.content_wrap.contentEditable = true;
    this.el_editor_wrap.appendChild(this.content_wrap);
    this.content_wrap.classList.add('el_cont_area');
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
    {
      var range = document.getSelection();
      var selectedElement = document.getSelection().anchorNode.parentElement;

      var id = selectedElement.parentNode.id;

      /*else */if(sValue=="p"||sValue=="h2"||sValue=="h3")
      {
        var tag = selectedElement.tagName;
        var getTag = sValue.toUpperCase();
        if(tag == getTag || tag =='LI'){} //do nothingid!='article-content'
        else
        {
          document.execCommand(sCmd, false, sValue); this.content_wrap.focus();
        }
      }
      else
      {
        document.execCommand(sCmd, false, sValue);// this.content_wrap.focus();
      }
    }
  }
  make_code_tag()
  {
    //later check
    var selectedElement = document.getSelection().anchorNode.parentElement;
    var wraper = selectedElement.parentElement;
    var tag = selectedElement.tagName;

    if( selectedElement.className == "code_line" ){}
    else
    {
    var elContent = selectedElement.innerHTML;
    var elSelection = document.getSelection();

    var anchorParent = elSelection.anchorNode.parentNode;
    var focusParent = elSelection.focusNode.parentNode;


    var stringBefore = anchorParent.innerHTML.substr(0,elSelection.anchorOffset);
    var stringAfter = focusParent.innerHTML.substr(elSelection.focusOffset);;

    var inOneNode = (anchorParent === focusParent);

    var anchorParentTag = anchorParent.tagName;
    var focusParentTag = focusParent.tagName;

    var strSelection = elSelection.toString();

    var div = this._el('div');
    div.className = 'code';
    //div.innerHTML = strSelection;
    var range;
    var range_cont;
    var cont;

    if (elSelection.rangeCount) {
      range = elSelection.getRangeAt(0);//.cloneRange();
      range_cont = range.extractContents();
      range.deleteContents();
      range.insertNode(div);
    }

    div.appendChild(range_cont);
    cont = div.innerHTML;
    cont = '<div class="code_line">'+cont.split('<br>').join('</div><div class="code_line">')+'</div>';
    div.innerHTML = cont;

/*
    var realContent = elSelection.getRangeAt(0).extractContents();
    var temp = this._el('span');
    temp.appendChild(realContent);
    var content = temp.innerHTML;


    //there is problem when no <br>;
    var newContent = '<div class="code_line">'+content.split('<br>').join('</div><div class="code_line">')+'</div>';


    this.format_doc('insertHTML','<div class="code">'+newContent+'</div>');
    */}
  }
  remove_format()
  {
    // because of DIV - CODE element
    var selectedElement = document.getSelection().anchorNode.parentElement;
    var tag = selectedElement.tagName;
    var wraper = selectedElement.parentElement;

    if (wraper.className == 'code')
    {
        var newContent = '';

        if(wraper.hasChildNodes())
        {
          var newContent = '';
          var children = wraper.childNodes;
          for(var i=0; i< children.length; i++){
            var lineEnd = '<br/>';
            if(i==(children.length - 1)){lineEnd = '';}else{}
            newContent += children[i].innerHTML+lineEnd;
          }
        }
        var p = this._el('p');
        p.innerHTML = newContent;
        wraper.parentNode.insertBefore(p, wraper);
        wraper.parentNode.removeChild(wraper);
    }
    else{
    this.format_doc('removeFormat');
    this.format_doc('formatblock','p');
    }
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

  generate_buttons()
  {
    var self = this;
    var oPlacement = this.btn_wrap;

    var resource_folder = '/icons-editor/';
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
        btn_event: function(){self.make_code_tag();}
      },
      Print: {
        nicename:'Print',
        fname:'print.png',
        btn_event: function(){self.printDoc();}
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
      self.form_area.addEventListener('submit',function(){
      // event.preventDefault();
        if(self.validate_mode())
        {
          // this => form_area
          this.content.value=self.content_wrap.innerHTML;
          return true;
        }
        return false;
      },false);
    }
    populate();
  }
}
