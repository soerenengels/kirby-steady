(function(){"use strict";function a(s,t,e,n,B,r,R,z){var i=typeof s=="function"?s.options:s;return t&&(i.render=t,i.staticRenderFns=e,i._compiled=!0),r&&(i._scopeId="data-v-"+r),{exports:s,options:i}}const o={props:{newsletterSubscribers:Object,plans:Object,plugin:Object,publication:Object,reports:Array,subscriptions:Object,subtab:String,tab:String,widgets:Array,widgetsEnabled:Boolean,widgetsWarning:Boolean},data(){return{views:{insights:"chart",widgets:this.widgetsEnabled?"toggle-on":"toggle-off",users:"users",debug:"code"}}},computed:{steadyUrl(){return`https://steadyhq.com/de/backend/publications/${this.publication.id}/home`},filteredSubscriptions(){return this.subscriptions.filter(s=>s.monthly_amount<500)},tabs(){return Object.entries(this.views).map(([s,t])=>{const e=(["users","debug"].includes(s)?"?tab=":"")+(s==="users"?"subscribers":s=="debug"?"publication":"");return{name:s,label:this.$t(`soerenengels.steady.${s}`),icon:t,link:`steady/${s}${e}`}})}}};var l=function(){var t=this,e=t._self._c;return e("k-panel-inside",[e("k-view",{staticClass:"k-steady"},[e("k-header",{staticClass:"k-steady-header",scopedSlots:t._u([{key:"buttons",fn:function(){return[e("k-button",{attrs:{icon:"edit",link:`https://steadyhq.com/de/backend/publications/${t.publication.id}/posts/new`}},[t._v(t._s(t.$t("soerenengels.steady.create.post")))]),e("k-button",{attrs:{icon:"github",link:t.plugin.link,title:"Version "+t.plugin.version}},[t._v("v"+t._s(t.plugin.version))]),e("k-button",{attrs:{icon:"book",link:t.plugin.link}},[t._v("Docs")]),e("k-button",{attrs:{icon:"steady",link:t.steadyUrl}})]},proxy:!0}])},[t._v(" "+t._s(t.publication.title)+" ")]),e("k-tabs",{attrs:{tabs:t.tabs,tab:t.tab}}),t.tab=="insights"?e("k-steady-tab-insights",{attrs:{reports:t.reports}}):t._e(),t.tab=="widgets"?e("k-steady-tab-widgets",{attrs:{widgets:t.widgets,widgetsEnabled:t.widgetsEnabled,widgetsWarning:t.widgetsWarning}}):t._e(),t.tab=="users"?e("k-steady-tab-users",{attrs:{members:t.subscriptions,subscribers:t.newsletterSubscribers,tab:t.subtab}}):t._e(),t.tab=="debug"?e("k-steady-tab-debug",{attrs:{tab:t.subtab,publication:t.publication,plans:t.plans,members:t.subscriptions,subscribers:t.newsletterSubscribers,reports:t.reports,widgets:t.widgets}}):t._e(),t.tab=="oauth"?e("k-steady-tab-oauth"):t._e()],1)],1)},c=[],d=a(o,l,c,!1,null,null);const u=d.exports,b={props:{reports:{type:Array,default:()=>[],required:!0}}};var h=function(){var t=this,e=t._self._c;return e("k-section",{attrs:{headline:t.$t("soerenengels.steady.insights")}},[e("k-stats",{attrs:{reports:t.reports,size:"huge"}})],1)},p=[],_=a(b,h,p,!1,null,null);const g=_.exports,m={props:{widgets:Array,widgetsEnabled:Boolean,widgetsWarning:Boolean},computed:{showNotice(){return this.showNoticeActivated||this.showNoticeDisabled},showNoticeActivated(){return this.widgetsEnabled&&!this.widgetsWarning},showNoticeDisabled(){return!this.widgetsEnabled&&this.widgetsWarning},notice(){return this.$t("soerenengels.steady.widgets.notice."+(this.showNoticeActivated?"activated":"disabled"))}}};var w=function(){var t=this,e=t._self._c;return e("k-section",{attrs:{headline:t.$t("soerenengels.steady.widgets.label")}},[t.showNotice?e("k-box",{attrs:{icon:"info",text:t.notice,theme:t.showNoticeActivated?"info":"notice"}}):t._e(),e("k-stats",{class:{"k-stats-widgets":!0,inactive:!t.widgetsEnabled},attrs:{reports:t.widgets,size:"medium"}}),e("k-text",{attrs:{html:t.$t("soerenengels.steady.widgets.help")}})],1)},y=[],f=a(m,w,y,!1,null,"2f5c30d9");const v=f.exports,k={props:{tab:String,members:Array,subscribers:Array},data(){return{page:1,views:{subscribers:"email",members:"users"},cols:{subscribers:{email:{label:this.$t("email"),mobile:!0},opted_in_at:{label:this.$t("soerenengels.steady.subscribed"),type:"steadyDate",width:"1/4",align:"right",mobile:!0}},members:{id:{label:this.$t("soerenengels.steady.id")},active_from:{label:this.$t("soerenengels.steady.activated"),type:"steadyDate",width:"1/6",align:"right",mobile:!0},monthly_amount:{label:this.$t("soerenengels.steady.monthly-amount"),mobile:!0,before:"€",width:"1/4",type:"number"},period:{label:this.$t("soerenengels.steady.period"),type:"tags",width:"1/8"},state:{label:this.$t("soerenengels.steady.state"),type:"tags",width:"1/8"}}}}},created(){this.$events.on("steady.subscriptions.cancelled",()=>{this.$panel.notification.success({icon:"check",message:this.$t("soerenengels.steady.subscriptions.cancelled"),type:"success"})})},methods:{options(s){return[{text:this.$t("soerenengels.steady.subscriptions.cancel"),icon:"cancel",click:()=>this.$dialog(`steady/subscriptions/cancel/${s.id}`)}]}},computed:{tabs(){return Object.entries(this.views).map(([s,t])=>({name:s,label:this.$t(`soerenengels.steady.${s}`),icon:t,click:()=>{this.tab=s}}))},rows(){var s;return(s=this[this.tab])==null?void 0:s.map(t=>({...t,monthly_amount:t.monthly_amount/100}))},columns(){return this.cols[this.tab]},pagination(){var s;return{page:this.page,details:!0,limit:25,total:(s=this.rows)==null?void 0:s.length}},index(){return(this.pagination.page-1)*this.pagination.limit+1},paginatedItems(){return this.rows.sort((s,t)=>{const e=s.hasOwnProperty("opted_in_at")?"opted_in_at":"active_from";return new Date(s[e].date)>=new Date(t[e].date)}).slice(this.index-1,this.pagination.limit*this.pagination.page)}}};var C=function(){var t=this,e=t._self._c;return e("div",[e("k-headline",[t._v(t._s(t.$t("soerenengels.steady.users.headline")))]),e("k-tabs",{staticClass:"k-tabs",attrs:{tabs:t.tabs,tab:t.tab}}),e("k-table",{attrs:{columns:t.columns,index:!1,pagination:t.pagination,rows:t.paginatedItems},on:{paginate:n=>this.page=n.page},scopedSlots:t._u([t.tab=="members"?{key:"options",fn:function({row:n}){return[e("k-options-dropdown",{attrs:{options:t.options(n)}})]}}:null],null,!0)})],1)},$=[],O=a(k,C,$,!1,null,"088ce728");const A=O.exports,S={props:{members:Object,plans:Array,publication:Object,reports:Array,subscribers:Array,tab:String},data(){return{language:"json",views:{publication:"page",plans:"store",subscribers:"email",members:"users",reports:"chart"}}},computed:{code(){return this[this.tab]},tabs(){return Object.entries(this.views).map(([s,t])=>({name:s,label:this.$t(`soerenengels.steady.${s}`),icon:t,click:()=>{this.tab=s}}))}}};var x=function(){var t=this,e=t._self._c;return e("div",[e("k-headline",[t._v(t._s(t.$t("soerenengels.steady.debug.headline")))]),e("k-tabs",{attrs:{tabs:t.tabs,tab:t.tab}}),e("k-section",[e("k-code",{attrs:{language:t.language}},[t._v(t._s(t.code))])],1)],1)},j=[],D=a(S,x,j,!1,null,"a58bcbe5");const L=D.exports,E={};var V=function(){var t=this,e=t._self._c;return e("k-grid",{attrs:{variant:"columns"}},[e("k-column",{attrs:{width:"1/2"}},[e("k-section",{attrs:{headline:"Setup"}},[e("k-text",[t._v("If you want to use the OAuth feature of the kirby-steady plugin, you need to get the OAuth credentials from the Steady backend and add them to your sites config file.")]),e("pre",[e("k-code",{attrs:{language:"php"}},[t._v(`<?php
	return [
		'soerenengels.steady.oauth' => [
			'key' => '...',
			'secret' => '...'
		]
	];
`)])],1)],1)],1),e("k-column",{attrs:{width:"1/2"}},[e("k-section",{attrs:{headline:"User Flow"}},[e("k-text",[e("ol",[e("li",[t._v(" To use the Steady OAuth Flow, the user needs to visit the `/oauth/steady/authorize` route. At this route a session cookie gets stored and the user will be redirected to steady, to authorize. ")]),e("li",[t._v(" When the user authorized the application, they will be redirected to the `/oauth/steady/callback` route. The verification string will be checked and the access token will be stored. ")])])])],1)],1),e("k-column",{attrs:{width:"1/2"}},[e("k-section",{attrs:{headline:"$steady->oauth->user()"}},[e("k-code",{attrs:{language:"json"}},[t._v("{ user }")])],1)],1),e("k-column",{attrs:{width:"1/2"}},[e("k-section",{attrs:{headline:"$steady->oauth->subscription()"}},[e("k-code",{attrs:{language:"json"}},[t._v("{ subscription }")])],1)],1)],1)},F=[],N=a(E,V,F,!1,null,null);const W=N.exports;panel.plugin("soerenengels/kirby-steady",{use:{plugin(s){window.__VUE_DEVTOOLS_GLOBAL_HOOK__&&(window.__VUE_DEVTOOLS_GLOBAL_HOOK__.Vue=s)}},components:{"k-steady-view":u,"k-steady-tab-insights":g,"k-steady-tab-widgets":v,"k-steady-tab-users":A,"k-steady-tab-debug":L,"k-steady-tab-oauth":W,"k-steadyDate-field-preview":{props:{value:Object},template:'<p class="k-text-field-preview">{{ (new Date(value.date)).toLocaleDateString("de-DE") }}</p>'}},icons:{steady:'<svg viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 40C31.0457 40 40 31.0457 40 20C40 8.9543 31.0457 0 20 0C8.9543 0 0 8.9543 0 20C0 31.0457 8.9543 40 20 40Z" fill="#291E38"/><g clip-path="url(#clip0_1401_20468)"><path opacity="0.5" d="M19.989 22.174L19.989 22.1766C19.7808 22.2193 19.4684 22.2833 19.0518 22.3686C18.1687 22.5433 17.4849 22.8764 17.0002 23.3678C16.5156 23.8592 16.2733 24.4434 16.2733 25.1204C16.2733 25.3506 16.2972 25.5687 16.3452 25.7749L12.129 27.0699C11.8763 26.3632 11.75 25.6152 11.75 24.8256C11.75 23.1439 12.31 21.6806 13.4301 20.4358C14.5501 19.1909 16.1225 18.3719 18.1472 17.9788C18.9629 17.8582 19.5755 17.7582 19.985 17.6786V17.6777C20.2024 17.6358 20.5284 17.5591 20.9631 17.4476C22.6181 17.124 23.4455 16.2612 23.4455 14.8592C23.4455 14.7301 23.4368 14.6039 23.4194 14.4808C23.4054 14.382 23.3858 14.2812 23.3606 14.1863L27.495 12.9182C27.7398 13.6202 27.8622 14.3791 27.8622 15.1827C27.8622 16.8868 27.3249 18.3104 26.2503 19.4536C25.1757 20.5968 23.7142 21.3625 21.8658 21.7508C21.0328 21.948 20.4072 22.0891 19.989 22.174Z" fill="white"/><path d="M19.9887 8.00035C22.1443 8.01807 23.9869 8.70818 25.5249 10.0707C26.4641 10.9028 27.1202 11.8524 27.4933 12.9195L23.3599 14.1877C23.222 13.67 22.9174 13.209 22.4461 12.8047C21.8047 12.2544 20.9886 11.969 19.9887 11.9485C19.9595 11.9481 19.9252 11.9473 19.8956 11.9473C18.8425 11.9473 17.9828 12.2331 17.3165 12.8047C16.6502 13.3763 16.3171 14.0611 16.3171 14.8592C16.3171 16.2612 17.1446 17.124 18.7995 17.4476C19.328 17.5508 19.724 17.6278 19.9874 17.6785V17.6777C20.397 17.7573 21.0113 17.8577 21.8304 17.9788C23.8551 18.3719 25.4274 19.1909 26.5475 20.4358C27.6675 21.6806 28.2276 23.1439 28.2276 24.8256C28.2276 26.7475 27.4791 28.4237 25.9821 29.8542C24.4851 31.2847 22.5089 32 19.9887 32C17.4686 32 15.4924 31.2847 13.9954 29.8542C13.1136 29.0116 12.4915 28.0837 12.1292 27.0705L16.3454 25.776C16.4751 26.3325 16.7795 26.802 17.2586 27.1843C17.9156 27.7085 18.8256 27.9705 19.9887 27.9705C21.1519 27.9705 22.0619 27.7085 22.7189 27.1843C23.3758 26.6601 23.7043 25.9722 23.7043 25.1204C23.7043 24.4434 23.462 23.8592 22.9773 23.3678C22.4927 22.8764 21.8088 22.5433 20.9257 22.3686C20.5093 22.2833 20.197 22.2193 19.9887 22.1767V22.1779C19.5247 22.0838 18.8274 21.9414 17.8968 21.7507C16.0484 21.3625 14.587 20.5968 13.5123 19.4536C12.4377 18.3104 11.9004 16.8868 11.9004 15.1827C11.9004 13.1552 12.6795 11.4512 14.2377 10.0707C15.7959 8.69023 17.6711 8 19.8634 8C19.9039 8 19.9485 8.00017 19.9887 8.00052V8.00035Z" fill="white"/></g><defs><clipPath id="clip0_1401_20468"><rect width="16.4776" height="24" fill="white" transform="translate(11.75 8)"/></clipPath></defs></svg>'}})})();
