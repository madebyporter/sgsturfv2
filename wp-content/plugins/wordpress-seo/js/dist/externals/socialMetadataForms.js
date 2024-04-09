(()=>{"use strict";var e={n:t=>{var i=t&&t.__esModule?()=>t.default:()=>t;return e.d(i,{a:i}),i},d:(t,i)=>{for(var o in i)e.o(i,o)&&!e.o(t,o)&&Object.defineProperty(t,o,{enumerable:!0,get:i[o]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t),r:e=>{"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})}},t={};e.r(t),e.d(t,{FACEBOOK_IMAGE_SIZES:()=>I,SocialMetadataPreviewForm:()=>W,TWITTER_IMAGE_SIZES:()=>w,actions:()=>i,determineFacebookImageMode:()=>b,selectorsFactory:()=>T,socialReducer:()=>R});var i={};e.r(i),e.d(i,{CLEAR_SOCIAL_IMAGE:()=>c,SET_SOCIAL_DESCRIPTION:()=>r,SET_SOCIAL_IMAGE:()=>l,SET_SOCIAL_IMAGE_ID:()=>s,SET_SOCIAL_IMAGE_TYPE:()=>a,SET_SOCIAL_IMAGE_URL:()=>n,SET_SOCIAL_TITLE:()=>o,clearSocialPreviewImage:()=>E,setSocialPreviewDescription:()=>p,setSocialPreviewImage:()=>h,setSocialPreviewImageId:()=>u,setSocialPreviewImageType:()=>g,setSocialPreviewImageUrl:()=>m,setSocialPreviewTitle:()=>d});const o="SET_SOCIAL_TITLE",r="SET_SOCIAL_DESCRIPTION",n="SET_SOCIAL_IMAGE_URL",a="SET_SOCIAL_IMAGE_TYPE",s="SET_SOCIAL_IMAGE_ID",l="SET_SOCIAL_IMAGE",c="CLEAR_SOCIAL_IMAGE",d=(e,t)=>({type:o,platform:t,title:e}),p=(e,t)=>({type:r,platform:t,description:e}),m=(e,t)=>({type:n,platform:t,imageUrl:e}),g=(e,t)=>({type:a,platform:t,imageType:e}),u=(e,t)=>({type:s,platform:t,imageId:e}),h=(e,t)=>({type:l,platform:t,image:e}),E=e=>({type:c,platform:e}),S=window.lodash,T=e=>{const t={getFacebookData:t=>(0,S.get)(t,`${e}.facebook`,{}),getFacebookTitle:e=>t.getFacebookData(e).title,getFacebookDescription:e=>t.getFacebookData(e).description,getFacebookImageUrl:e=>t.getFacebookData(e).image.url,getFacebookImageType:e=>t.getFacebookData(e).image.type,getTwitterData:t=>(0,S.get)(t,`${e}.twitter`,{}),getTwitterTitle:e=>t.getTwitterData(e).title,getTwitterDescription:e=>t.getTwitterData(e).description,getTwitterImageUrl:e=>t.getTwitterData(e).image.url,getTwitterImageType:e=>t.getTwitterData(e).image.type};return t},w={squareWidth:125,squareHeight:125,landscapeWidth:506,landscapeHeight:265,aspectRatio:50.2},I={squareWidth:158,squareHeight:158,landscapeWidth:527,landscapeHeight:273,portraitWidth:158,portraitHeight:237,aspectRatio:52.2,largeThreshold:{width:446,height:233}},b=function(e){const{largeThreshold:t}=I;return e.height>e.width?"portrait":e.width<t.width||e.height<t.height||e.height===e.width?"square":"landscape"},v=window.yoast.redux,_={title:"",description:"",warnings:[],image:{bytes:null,type:null,height:null,width:null,url:"",id:null,alt:""}};function f(e=_,t){switch(t.type){case o:return{...e,title:t.title};case r:return{...e,description:t.description};case l:return{...e,image:{...t.image}};case n:return{...e,image:{...e.image,url:t.imageUrl}};case a:return{...e,image:{...e.image,type:t.imageType}};case s:return{...e,image:{...e.image,id:t.imageId}};case c:return{...e,image:{bytes:null,type:null,height:null,width:null,url:"",id:null,alt:""}};default:return e}}function y(e,t){return(i,o)=>{const{platform:r}=o;return void 0===i?_:r!==t?i:e(i,o)}}const R=(0,v.combineReducers)({facebook:y(f,"facebook"),twitter:y(f,"twitter")}),A=window.React,C=window.wp.i18n,P=window.yoast.helpers,M=window.yoast.replacementVariableEditor,D=window.yoast.styleGuide,L=window.yoast.propTypes;var F=e.n(L);const O=window.yoast.styledComponents;var k=e.n(O);const H=window.yoast.componentsNew,x=e=>e?D.colors.$color_snippet_focus:D.colors.$color_snippet_hover,V=k().div`
	position: relative;

	${e=>!e.isPremium&&"\n\t\t.yoast-image-select__preview {\n\t\t\twidth: 130px;\n\t\t\tmin-height: 72px;\n\t\t\tmax-height: 130px;\n\t\t}\n\t"};
`;V.propTypes={isPremium:F().bool},V.defaultProps={isPremium:!1};const q=k().div`
	display: ${e=>e.isActive||e.isHovered?"block":"none"};

	::before {
		position: absolute;
		top: -2px;
		${(0,P.getDirectionalStyle)("left","right")}: -25px;
		width: 24px;
		height: 24px;
		background-image: url(
		${e=>(0,P.getDirectionalStyle)((0,D.angleRight)(x(e.isActive)),(0,D.angleLeft)(x(e.isActive)))}
		);
		color: ${e=>x(e.isActive)};
		background-size: 24px;
		background-repeat: no-repeat;
		background-position: center;
		content: "";
	}
`;q.propTypes={isActive:F().bool,isHovered:F().bool},q.defaultProps={isActive:!1,isHovered:!1};const G=(U=H.ImageSelect,function e(t){e.propTypes={isActive:F().bool.isRequired,isHovered:F().bool.isRequired,isPremium:F().bool};const{isActive:i,isHovered:o,isPremium:r,...n}=t;return(0,A.createElement)(V,{isPremium:r},(0,A.createElement)(q,{isActive:i,isHovered:o}),(0,A.createElement)(U,{...n}))});var U;class j extends A.Component{constructor(e){super(e),this.onImageEnter=e.onMouseHover.bind(this,"image"),this.onTitleEnter=e.onMouseHover.bind(this,"title"),this.onDescriptionEnter=e.onMouseHover.bind(this,"description"),this.onLeave=e.onMouseHover.bind(this,""),this.onImageSelectBlur=e.onSelect.bind(this,""),this.onSelectTitleEditor=this.onSelectEditor.bind(this,"title"),this.onSelectDescriptionEditor=this.onSelectEditor.bind(this,"description"),this.onDeselectEditor=this.onSelectEditor.bind(this,""),this.onTitleEditorRef=this.onSetEditorRef.bind(this,"title"),this.onDescriptionEditorRef=this.onSetEditorRef.bind(this,"description")}onSelectEditor(e){this.props.onSelect(e)}onSetEditorRef(e,t){this.props.setEditorRef(e,t)}getFieldsTitles(e){return"Twitter"===e?{imageSelectTitle:(0,C.__)("Twitter image","wordpress-seo"),titleEditorTitle:(0,C.__)("Twitter title","wordpress-seo"),descEditorTitle:(0,C.__)("Twitter description","wordpress-seo")}:"X"===e?{imageSelectTitle:(0,C.__)("X image","wordpress-seo"),titleEditorTitle:(0,C.__)("X title","wordpress-seo"),descEditorTitle:(0,C.__)("X description","wordpress-seo")}:{imageSelectTitle:(0,C.__)("Social image","wordpress-seo"),titleEditorTitle:(0,C.__)("Social title","wordpress-seo"),descEditorTitle:(0,C.__)("Social description","wordpress-seo")}}render(){const{socialMediumName:e,onSelectImageClick:t,onRemoveImageClick:i,title:o,titleInputPlaceholder:r,description:n,descriptionInputPlaceholder:a,onTitleChange:s,onDescriptionChange:l,onReplacementVariableSearchChange:c,hoveredField:d,activeField:p,isPremium:m,replacementVariables:g,recommendedReplacementVariables:u,imageWarnings:h,imageUrl:E,imageAltText:S,idSuffix:T}=this.props,w=this.getFieldsTitles(e),I=!!E,b=w.imageSelectTitle,v=w.titleEditorTitle,_=w.descEditorTitle,f=e.toLowerCase();return(0,A.createElement)(A.Fragment,null,(0,A.createElement)(G,{label:b,onClick:t,onRemoveImageClick:i,warnings:h,imageSelected:I,onMouseEnter:this.onImageEnter,onMouseLeave:this.onLeave,isActive:"image"===p,isHovered:"image"===d,imageUrl:E,imageAltText:S,hasPreview:!m,imageUrlInputId:(0,P.join)([f,"url-input",T]),selectImageButtonId:(0,P.join)([f,"select-button",T]),replaceImageButtonId:(0,P.join)([f,"replace-button",T]),removeImageButtonId:(0,P.join)([f,"remove-button",T]),isPremium:m}),(0,A.createElement)(M.ReplacementVariableEditor,{onChange:s,content:o,placeholder:r,replacementVariables:g,recommendedReplacementVariables:u,type:"title",fieldId:(0,P.join)([f,"title-input",T]),label:v,onMouseEnter:this.onTitleEnter,onMouseLeave:this.onLeave,onSearchChange:c,isActive:"title"===p,isHovered:"title"===d,withCaret:!0,onFocus:this.onSelectTitleEditor,onBlur:this.onDeselectEditor,editorRef:this.onTitleEditorRef}),(0,A.createElement)(M.ReplacementVariableEditor,{onChange:l,content:n,placeholder:a,replacementVariables:g,recommendedReplacementVariables:u,type:"description",fieldId:(0,P.join)([f,"description-input",T]),label:_,onMouseEnter:this.onDescriptionEnter,onMouseLeave:this.onLeave,onSearchChange:c,isActive:"description"===p,isHovered:"description"===d,withCaret:!0,onFocus:this.onSelectDescriptionEditor,onBlur:this.onDeselectEditor,editorRef:this.onDescriptionEditorRef}))}}j.propTypes={socialMediumName:F().oneOf(["Twitter","X","Social"]).isRequired,onSelectImageClick:F().func.isRequired,onRemoveImageClick:F().func.isRequired,title:F().string.isRequired,description:F().string.isRequired,onTitleChange:F().func.isRequired,onDescriptionChange:F().func.isRequired,onReplacementVariableSearchChange:F().func,isPremium:F().bool,hoveredField:F().string,activeField:F().string,onSelect:F().func,replacementVariables:M.replacementVariablesShape,recommendedReplacementVariables:F().arrayOf(F().string),imageWarnings:F().array,imageUrl:F().string,imageAltText:F().string,titleInputPlaceholder:F().string,descriptionInputPlaceholder:F().string,setEditorRef:F().func,onMouseHover:F().func,idSuffix:F().string},j.defaultProps={replacementVariables:[],recommendedReplacementVariables:[],imageWarnings:[],hoveredField:"",activeField:"",onSelect:()=>{},onReplacementVariableSearchChange:null,imageUrl:"",imageAltText:"",titleInputPlaceholder:"",descriptionInputPlaceholder:"",isPremium:!1,setEditorRef:()=>{},onMouseHover:()=>{},idSuffix:""};const W=j;(window.yoast=window.yoast||{}).socialMetadataForms=t})();