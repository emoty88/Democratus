<?php 
class jstemplates_block extends control{    
	public function block(){
	?>
		<script id="duvaryazisi-tmpl" type="text/x-jquery-tmpl">
			<!-- Duvar Yazısı -->
			<div id="voiceSliceTop_${ID}-${randNum}" class="dyazi_top_slice" ></div>
			<article class="duvar_yazisi" >
				<div class="voice_hover_area" style="" onclick="voiceDetail(this);" data-voiceID="${ID}" data-randNum="${randNum}" >
					<img class="profil_resmi" src="${sImage}" alt="${sName}">
					<address class="yazar">
						<a href="/${sPerma}" title="${sName} Profilini Görüntüle" onclick="notOpen=1;">${sName}</a> 
						<span>${sTime} Önce</span>
					</address>
					<div class="duvar_yazisi_icerigi">
						<p>{{html voice}}</p>
					</div>
					<aside class="komutlar" onclick="notOpen=1;">
						{{if redierName}}
							<a href="/${redierPerma}"><i class="atolye15-ikon-paylas atolye15-ikon-24"></i> ${redierName} Tarafından paylaşıldı</a> 
							<br />
						{{/if}}
						{{if initem>0}}
							<a href="javascript:;" onclick="ac_kapa_manual(this);" data-tetikleyici="ac-kapa" data-hedef="#fotograf_${ID}" data-vazgec-metni="Fotoğrafı Gizle" data-metni="Fotoğrafı Göster" data-voiceID="${ID}" >
								<i class="atolye15-ikon-gorsel atolye15-ikon-24"></i> <span>Fotoğrafı Göster</span>
							</a>
							<div id="fotograf_${ID}" style="display:none;">
								<img id="voice_resim_${ID}" class="duvar_fotografi" src="" alt="">
							</div>
						{{/if}}
						<a id="soyles_btn_${ID}" href="/voice/${ID}">
							<i class="atolye15-ikon-soylesi atolye15-ikon-24"></i> 
							Söyleş {{if replyCount>0}}(${replyCount}){{/if}}
						</a>
						<a id="paylas_btn_${ID}" href="javascript:redi(${ID});">
							<i class="atolye15-ikon-paylas atolye15-ikon-24"></i> 
							<span>Paylaş</span>
						</a>
						<a id="taktir_btn_${ID}" href="javascript:voice_like(${ID}, 1);">
							<i class="atolye15-ikon-taktir atolye15-ikon-24"></i> 
							<span>Taktir Et</span>
						</a>
						<a id="saygi_btn_${ID}" href="javascript:voice_like(${ID}, 2);">
							<i class="atolye15-ikon-saygi atolye15-ikon-24"></i> 
							<span>Saygı Duy</span>
						</a>
					</aside>
				</div>
				<aside id="voiceReplyArea_${ID}-${randNum}" class="yorumlar" data-isload="0" style="display: none;" onclick="notOpen=1;" ></aside>
			</article>
				<aside id="voice_detailArea_${ID}-${randNum}" style="display: none;" data-isOpen="0" >
					<div id="replyArea_${ID}-${randNum}" class="replyArea">
						<textarea id="replyTextArea_${ID}-${randNum}" class="karakterSay" onfocus="replyTextFocus(${ID},${randNum});" onblur="replyTextBlur(${ID},${randNum});"  rows="1" >+voice </textarea>
						<div class="reply_bottom">
							<div class="kalan_karakter_mesaji">
								<span id="replyArea_${ID}-${randNum}Number" class="karakter_sayaci">200</span> karakter
								</div>
							<div class="kontroller_voice">
							
								<button class="btn btn-danger" id="replyArea_${ID}-${randNum}Button" onclick="share_voice(this)" data-randID="${ID}-${randNum}" >Paylaş</button>
								<a href="javascript:void(0)" id="fine-uploader-btn" style="position: relative; overflow: hidden; direction: ltr;" class="">
									<i class="atolye15-ikon-gorsel atolye15-ikon-24" id="bootstrapped-fine-uploader"></i>
								<input type="file" name="file" style="position: absolute; right: 0px; top: 0px; font-family: Arial; font-size: 118px; margin: 0px; padding: 0px; cursor: pointer; opacity: 0;"></a>
								<div id="fine-uploader-msg"></div>	
								
								<input type="hidden" id="replyer_${ID}-${randNum}" value="${ID}" />
							</div>
							<div style="clear: both;"> </div>
						</div>
						
					</div>
				</aside>
			<div id="voiceSliceBottom_${ID}-${randNum}" class="dyazi_bottom_slice" ></div>
			<!-- Duvar Yazısı Son -->
		</script>
		
		<script id="voice-reply-tmpl" type="text/x-jquery-tmpl">
			<article style="display: block;" class="yorum">
				<div class="yorum_tutucu_arkaplan">
					<div class="yorum_tutucu">
						<img class="profil_resmi" src="${sImage}" alt="${sName}">
						<address class="yazar">
							<a href="/${sPerma}" title="${sName} Profilini Görüntüle" onclick="notOpen=1;">${sName}</a> 
							<span>${sTime} önce</span>
						</address>
						<div class="duvar_yazisi_icerigi">
							<p>{{html voice}}</p>
						</div>
						<aside class="komutlar" onclick="notOpen=1;">
							{{if redierName}}
								<a href="/${redierPerma}"><i class="atolye15-ikon-paylas atolye15-ikon-24"></i> ${redierName} Tarafından paylaşıldı</a> 
								<br />
							{{/if}}
							{{if initem>0}}
								<a href="javascript:;" onclick="ac_kapa_manual(this);" data-tetikleyici="ac-kapa" data-hedef="#fotograf_${ID}" data-vazgec-metni="Fotoğrafı Gizle" data-metni="Fotoğrafı Göster" data-voiceID="${ID}" >
									<i class="atolye15-ikon-gorsel atolye15-ikon-24"></i> <span>Fotoğrafı Göster</span>
								</a>
								<div id="fotograf_${ID}" style="display:none;">
									<img id="voice_resim_${ID}" class="duvar_fotografi" src="" alt="">
								</div>
							{{/if}}
							<a id="soyles_btn_${ID}" href="/voice/${ID}">
								<i class="atolye15-ikon-soylesi atolye15-ikon-24"></i> 
								Söyleş {{if replyCount>0}}(${replyCount}){{/if}}
							</a>
							<a id="paylas_btn_${ID}" href="javascript:redi(${ID});">
								<i class="atolye15-ikon-paylas atolye15-ikon-24"></i> 
								<span>Paylaş</span>
							</a>
							<a id="taktir_btn_${ID}" href="javascript:voice_like(${ID}, 1);">
								<i class="atolye15-ikon-taktir atolye15-ikon-24"></i> 
								<span>Taktir Et</span>
							</a>
							<a id="saygi_btn_${ID}" href="javascript:voice_like(${ID}, 2);">
								<i class="atolye15-ikon-saygi atolye15-ikon-24"></i> 
								<span>Saygı Duy</span>
							</a>
						</aside>
					</div>
				</div>
			</article>
		</script>
		
		<script id="duvaryazisianket-tmpl" type="text/x-jquery-tmpl">
			<!-- Duvar Yazısı Anket -->
			<article class="duvar_yazisi anket">
				<div class="anket_tutucu_arkaplan">
					<div class="anket_tutucu">
						<img class="profil_resmi" src="http://lorempixel.com/48/48/people/1/" alt="Sedef Kul Profil Fotoğrafı">
						<address class="yazar"><a href="#" title="Sedef Kul'un Profilini Görüntüle">Derya Baykal</a> <span>2 gün, 2 saat</span></address>
						<div class="duvar_yazisi_icerigi">
							<p>Petrol konusunda ülkeler arasında olan karşılıklı bağımlılık yüzünden fiyatlar diken üstünde olabiliyor. Mesela bugün olacak olan Venezuella seçimleri. Her şeyin ne olacağı meçhul.</p>
						</div>
						<aside class="cevaplar">
							<a href="#" class="btn">Katılıyorum</a>
							<a href="#" class="btn">Kararsızım</a>
							<a href="#" class="btn">Katılmıyorum</a>
						</aside>
					</div>
				</div>
			</article>
			<!-- Duvar Yazısı Anket Son -->
		</script>
		
		<script id="dahafazlases-tmpl" type="text/x-jquery-tmpl">
			<aside class="daha_fazla_duvar_yazisi"><a href="javascript:;">Daha fazla Voice yükle...</a></aside>
		</script>
		
		<script id="loadingbar-tmpl" type="text/x-jquery-tmpl">
			<aside id="loading_bar" class="loading_bar">
				<img src="/t/ala/img/loading.gif" />
				<a href="javascript:;">Yükleniyor</a>
			</aside>
		</script>
		
		<script id="meclis-istatistik-tmpl" type="text/x-jquery-tmpl">
			{{if olumlu>0}}
			<div class="bar yuzdeler_meclis olumlu" style="width: ${olumlu}%;">${olumlu}</div>
			{{/if}}
			{{if olumsuz>0}}
			<div class="bar yuzdeler_meclis olumsuz" style="width: ${olumsuz}%;">${olumsuz}</div>
			{{/if}}
			{{if fikiryok>0}}
			<div class="bar yuzdeler_meclis fikir-yok" style="width: ${fikiryok}%;">${fikiryok}</div>
			{{/if}}
		</script>
		
		<script id="parliament-agenda-tmpl" type="text/x-jquery-tmpl">
			<article class="duvar_yazisi anket referandum {{if myVote == null}}yeni{{/if}}">
				<div class="anket_tutucu_arkaplan">
					<div class="anket_tutucu">
						<img alt="${dName} Profil Fotoğrafı" src="${dImage}" class="profil_resmi">
						<address class="yazar">
							<a title="Sedef Kul'un Profilini Görüntüle" href="#">${dName}</a> 
							<span>2 gün, 2 saat</span>
						</address>
						<div class="duvar_yazisi_icerigi">
							<p>
								${agendaT}
							</p>
						</div>
						<aside class="cevaplar">
							<a class="btn {{if myVote == 2}} btn-danger {{/if}} btn-agenda-${ID} btn-agenda-${ID}-2" data-type="parliementPage" href="javascript:set_meclis_oy(${ID}, 2);">Katılıyorum</a>
							<a class="btn {{if myVote == 4}} btn-danger {{/if}} btn-agenda-${ID} btn-agenda-${ID}-4" data-type="parliementPage" href="javascript:set_meclis_oy(${ID}, 4);">Katılmıyorum</a>
							<a class="btn {{if myVote == 3}} btn-danger {{/if}} btn-agenda-${ID} btn-agenda-${ID}-3" data-type="parliementPage" href="javascript:set_meclis_oy(${ID}, 3);">Kararsızım</a>
						</aside>
						
					</div>
					
				</div>
				{{if myVote == null}}
					<div class="yeni-ikon"></div>
				{{/if}}
			</article>
		</script>
		
		<script id="parliament-deputys-tmpl" type="text/x-jquery-tmpl">
			<li>
				<article>
					<img src="${image}">
					<aside class="vekil_bilgileri">
						<ul class="istatistik_listesi_2">
							{{if isfollow}}
								<li><button type="button" class="btn btn follow follow-${ID}" style="display: none;" onclick="follow(${ID});">Takip Et</button></li>
								<li><button type="button" class="btn btn-info unfollow unfollow-${ID}" style="" onclick="follow(${ID});" data-unfText="Takibi Bırak" data-fText="Takip Ediliyor">Takip Ediliyor</button></li>
							{{else}}
								<li><button type="button" class="btn btn follow follow-${ID}" style="" onclick="follow(${ID});">Takip Et</button></li>
								<li><button type="button" class="btn btn-info unfollow unfollow-${ID}" style="display: none;" onclick="follow(${ID});" data-unfText="Takibi Bırak" data-fText="Takip Ediliyor">Takip Ediliyor</button></li>
							{{/if}}
							<li><strong>${count_following}</strong> TAKİP ETTİĞİ</li>
							<li><strong>${count_follower}</strong> TAKİPÇİ</li>
						</ul>
					</aside>
					<hr>
					<header>
						<address>
							<h1><a title="${name}" href="#">${name}</a></h1>
						</address>
					</header>
					<p style="height: 100px;">${motto}</p>
					<aside class="istatistikler">
						<ul class="istatistik_listesi">
							<li><strong>${count_voice}</strong> SES</li>
							<li><strong>${count_like}</strong> TAKTİR</li>
							<li><strong>${count_dislike}</strong> SAYGI</li>
						</ul>
					</aside>
				</article>
			</li>
		</script>
		
		<script id="parliament-oldAgenda-tmpl" type="text/x-jquery-tmpl">
			<article class="duvar_yazisi anket referandum tamamlanmis ">
				<div class="anket_tutucu_arkaplan">
					<div class="anket_tutucu">
						<img alt="${dName} Profil Fotoğrafı" src="${dImage}" class="profil_resmi">
						<address class="yazar">
							<a title="${dName}'un Profilini Görüntüle" href="#">${dName}</a> 
							<span>2 gün, 2 saat</span>
						</address>
						<div class="duvar_yazisi_icerigi">
							<p>
								${agendaT}
							</p>
						</div>
						<aside class="komutlar">
							<a href="#"><i class="atolye15-ikon-soylesi atolye15-ikon-24"></i> Söyleş</a>
							<a href="#"><i class="atolye15-ikon-paylas atolye15-ikon-24"></i> Paylaş</a>
						</aside>
						{{if percent.sonuc == "olumlu"}}
						<aside class="cevaplar yuzde katiliyorum" style="cursor: pointer;" data-original-title="">
							<span class="sonuc"><strong>%${percent.max}</strong> Katılıyorum</span>
						{{else  percent.sonuc == "olumsuz"}}
						<aside class="cevaplar yuzde katilmiyorum" style="cursor: pointer;" data-original-title="">
							<span class="sonuc"><strong>%${percent.max}</strong> Katılmıyorum</span>
						{{else percent.sonuc == "fikiryok"}}
						<aside class="cevaplar yuzde kararsizim" style="cursor: pointer;" data-original-title="">
							<span class="sonuc"><strong>%${percent.max}</strong> Kararsızım</span>
						{{/if}}
							<div style="display:none">
								<p class="yuzdeler olumlu" style="width: ${percent.olumlu}%">${percent.olumlu}</p>
								<p class="yuzdeler olumsuz" style="width: ${percent.olumsuz}%">${percent.olumsuz}</p>
								<p class="yuzdeler fikir-yok" style="width: ${percent.fikiryok}%">${percent.fikiryok}</p>
							</div>
						</aside>
					</div>
				</div>
			</article>
		</script>
		
		<script id="parliament-deputyItem-tmpl" type="text/x-jquery-tmpl">
			<li>
				<a href="{{if ID > 0}} /${dPerma} {{else}} javascript:; {{/if}}">
					<img alt="" src="{{if ID > 0}} ${dImage} {{else}}/t/ala/img/vekil-yok.png {{/if}}" class="vekil_resmi">
					<div class="vekil_ismi" style="height: 35px;">{{if ID > 0}}${dName} {{else}} &nbsp; {{/if}}</div>
				</a> 
			</li>
		</script> 
		<script id="parliament-friendItem-tmpl" type="text/x-jquery-tmpl">
			<li>
				<img alt="" src="${pImage}" class="vekil_resmi">
				<address style="height: 30px;"><a title="${pName}" href="/${pPerma}">${pName}</a></address>
				<p><a class="btn" href="javascript:vekilOyu(${ID})">Oy Ver</a></p>
			</li>
		</script>
		
		<script id="parliament-proposal-tmpl" type="text/x-jquery-tmpl">
			<article class="duvar_yazisi anket referandum">
				<div class="anket_tutucu_arkaplan">
					<div class="anket_tutucu">
						<img class="profil_resmi" src="${dImage}" alt="${dName} Profil Fotoğrafı">
						<address class="yazar">
							<a href="/${dPerma}" title="${dName} Profilini Görüntüle">${dName}</a> 
							<span> 2 gün, 2 saat</span>
						</address>
						<div class="duvar_yazisi_icerigi">
							<p style="min-height: 48px;">${text}</p>
						</div>
						<aside class="cevaplar tasari_yaz_cevaplar">
							<a href="#" class="btn">Tartışılsın</a>
							<a href="#" class="btn">Tartışılmasın</a>
						</aside>
					</div>
				</div>
			</article>
		</script>
		
		<script id="message-dialog-tmpl" type="text/x-jquery-tmpl">
			<li>
				<article>
					<header>
						<address>
							<h1>
								<a title="${fName}" href="/${fPerma}">${fName}</a> 
								<small>@${fPerma}</small>
							</h1>
						</address>
					</header>
					<p style="min-height: 40px;">${message}</p>
					<img alt="" src="${fImage}">
					<a class="hoverlay" href="#">&nbsp;</a>
					<aside>
						<time datetime="${mTime}">${mTime}</time>
						<i class="icon-chevron-right"></i>
					</aside>
				</article>
			</li>
		</script>
		
		<script id="message-dialog-detail-tmpl" type="text/x-jquery-tmpl">
			<article class="{{if me}} sag one_cikarilmis {{else}} sol {{/if}}">
				<address><a title="${fName}" href="/${fPerma}">
					<img alt="${fName} - Profil Resmi" src="${fImage}"></a>
				</address>
				<aside>
					<a href="/${fPerma}">${fName}</a>
					<time datetime="${mTime}">${mTime}</time>
				</aside>
				<div>
					<p style="min-height: 32px;">${message}</p>
				</div>
			</article>
		</script>
		
		<script id="social-friendList-tmpl" type="text/x-jquery-tmpl">
			<article class="duvar_yazisi">
				<img alt="${pName} Profil Fotoğrafı" src="${pImage}" class="profil_resmi">
				<address class="yazar">
					<a title="${pName} Profilini Görüntüle" href="/${pPerma}">
						${pName} 
					</a>
					<a title="${pName} Profilini Görüntüle" href="/${pPerma}">
						<span>@${pPerma}</span>
					</a>	
				</address>
				<div class="duvar_yazisi_icerigi" style="width: 400px;">
					<p style="">${pMotto}</p>
				</div>
				<div style="position: absolute;top: 20px; left: 500px;">
					<button type="button" class="btn">Takip Et</button>
				</div>
				<div style="clear: both;"></div>
			</article>
		</script> 
	<?	
	}
}
