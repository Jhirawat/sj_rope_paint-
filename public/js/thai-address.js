// V5.2 Thai address cascading dropdown.
// Primary source: kongvut/thai-province-data via jsDelivr (all Thai provinces/districts/subdistricts/postcodes).
// Fallback keeps the form usable when offline/local network blocks CDN.
(function(){
  const CDN = {
    provinces:'https://cdn.jsdelivr.net/gh/kongvut/thai-province-data@master/api_province.json',
    districts:'https://cdn.jsdelivr.net/gh/kongvut/thai-province-data@master/api_amphure.json',
    subdistricts:'https://cdn.jsdelivr.net/gh/kongvut/thai-province-data@master/api_tambon.json'
  };
  const fallback = {
    provinces:[{id:1,name_th:'กรุงเทพมหานคร',name_en:'Bangkok'},{id:2,name_th:'เชียงใหม่',name_en:'Chiang Mai'},{id:3,name_th:'ประจวบคีรีขันธ์',name_en:'Prachuap Khiri Khan'},{id:4,name_th:'ชลบุรี',name_en:'Chon Buri'},{id:5,name_th:'ภูเก็ต',name_en:'Phuket'}],
    districts:[
      {id:101,name_th:'เขตบางรัก',name_en:'Bang Rak',province_id:1},{id:102,name_th:'เขตวัฒนา',name_en:'Watthana',province_id:1},{id:103,name_th:'เขตจตุจักร',name_en:'Chatuchak',province_id:1},
      {id:201,name_th:'เมืองเชียงใหม่',name_en:'Mueang Chiang Mai',province_id:2},{id:202,name_th:'สะเมิง',name_en:'Samoeng',province_id:2},{id:203,name_th:'หางดง',name_en:'Hang Dong',province_id:2},
      {id:301,name_th:'หัวหิน',name_en:'Hua Hin',province_id:3},{id:302,name_th:'เมืองประจวบคีรีขันธ์',name_en:'Mueang Prachuap Khiri Khan',province_id:3},
      {id:401,name_th:'เมืองชลบุรี',name_en:'Mueang Chon Buri',province_id:4},{id:402,name_th:'บางละมุง',name_en:'Bang Lamung',province_id:4},
      {id:501,name_th:'เมืองภูเก็ต',name_en:'Mueang Phuket',province_id:5},{id:502,name_th:'กะทู้',name_en:'Kathu',province_id:5}
    ],
    subdistricts:[
      {id:10101,name_th:'สีลม',name_en:'Si Lom',amphure_id:101,zip_code:'10500'},{id:10201,name_th:'คลองเตยเหนือ',name_en:'Khlong Toei Nuea',amphure_id:102,zip_code:'10110'},{id:10301,name_th:'จตุจักร',name_en:'Chatuchak',amphure_id:103,zip_code:'10900'},
      {id:20101,name_th:'สุเทพ',name_en:'Suthep',amphure_id:201,zip_code:'50200'},{id:20102,name_th:'ช้างเผือก',name_en:'Chang Phueak',amphure_id:201,zip_code:'50300'},{id:20201,name_th:'บ่อแก้ว',name_en:'Bo Kaeo',amphure_id:202,zip_code:'50250'},{id:20202,name_th:'สะเมิงใต้',name_en:'Samoeng Tai',amphure_id:202,zip_code:'50250'},{id:20301,name_th:'หางดง',name_en:'Hang Dong',amphure_id:203,zip_code:'50230'},
      {id:30101,name_th:'หัวหิน',name_en:'Hua Hin',amphure_id:301,zip_code:'77110'},{id:30102,name_th:'หนองแก',name_en:'Nong Kae',amphure_id:301,zip_code:'77110'},{id:30201,name_th:'ประจวบคีรีขันธ์',name_en:'Prachuap Khiri Khan',amphure_id:302,zip_code:'77000'},
      {id:40101,name_th:'แสนสุข',name_en:'Saen Suk',amphure_id:401,zip_code:'20130'},{id:40201,name_th:'หนองปรือ',name_en:'Nong Prue',amphure_id:402,zip_code:'20150'},
      {id:50101,name_th:'ตลาดใหญ่',name_en:'Talat Yai',amphure_id:501,zip_code:'83000'},{id:50201,name_th:'กะทู้',name_en:'Kathu',amphure_id:502,zip_code:'83120'}
    ]
  };
  const cache = { provinces:null, districts:null, subdistricts:null };
  async function getJson(url){
    const res = await fetch(url,{headers:{'Accept':'application/json'},cache:'force-cache'});
    if(!res.ok) throw new Error('Thai address data unavailable');
    return await res.json();
  }
  async function allProvinces(){ if(cache.provinces) return cache.provinces; try{cache.provinces=await getJson(CDN.provinces);}catch(e){cache.provinces=fallback.provinces;} return cache.provinces; }
  async function allDistricts(){ if(cache.districts) return cache.districts; try{cache.districts=await getJson(CDN.districts);}catch(e){cache.districts=fallback.districts;} return cache.districts; }
  async function allSubdistricts(){ if(cache.subdistricts) return cache.subdistricts; try{cache.subdistricts=await getJson(CDN.subdistricts);}catch(e){cache.subdistricts=fallback.subdistricts;} return cache.subdistricts; }
  function escapeHtml(s){ return String(s??'').replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m])); }
  function label(x){ return window.SJ_LOCALE === 'en' ? (x.name_en || x.name_th) : (x.name_th || x.name_en); }
  function setOptions(select, items, placeholder, oldValue){
    select.innerHTML = `<option value="">${placeholder}</option>` + items.map(x=>`<option value="${escapeHtml(x.name_th)}" data-id="${x.id}" data-name-en="${escapeHtml(x.name_en||'')}" data-zip="${x.zip_code||''}">${escapeHtml(label(x))}</option>`).join('');
    if(oldValue){
      const byValue = [...select.options].find(o => o.value === oldValue || o.textContent === oldValue || o.dataset.nameEn === oldValue);
      if(byValue) select.value = byValue.value;
    }
  }
  function selectedId(select){ return select.selectedOptions[0]?.dataset?.id || ''; }
  window.initThaiAddressCascader = async function(scope){
    const root = scope || document;
    const boxes = root.matches?.('[data-thai-address]') ? [root] : root.querySelectorAll('[data-thai-address]');
    boxes.forEach(async box=>{
      const province = box.querySelector('[data-province]'), district = box.querySelector('[data-district]'), subdistrict = box.querySelector('[data-subdistrict]'), postal = box.querySelector('[data-postal]');
      if(!province || !district || !subdistrict || !postal) return;
      const en = window.SJ_LOCALE === 'en';
      const old = { province: province.dataset.value || province.value, district: district.dataset.value || district.value, subdistrict: subdistrict.dataset.value || subdistrict.value, postal: postal.dataset.value || postal.value };
      async function loadProvinces(){ setOptions(province, await allProvinces(), en?'Select province':'เลือกจังหวัด', old.province); await loadDistricts(); }
      async function loadDistricts(){
        const items = (await allDistricts()).filter(x => String(x.province_id) === String(selectedId(province)));
        setOptions(district, items, en?'Select district':'เลือกอำเภอ/เขต', old.district);
        await loadSubdistricts();
      }
      async function loadSubdistricts(){
        const items = (await allSubdistricts()).filter(x => String(x.amphure_id) === String(selectedId(district)));
        setOptions(subdistrict, items, en?'Select subdistrict':'เลือกตำบล/แขวง', old.subdistrict);
        loadPostal();
      }
      function loadPostal(){ postal.value = subdistrict.selectedOptions[0]?.dataset?.zip || old.postal || ''; }
      province.addEventListener('change', async()=>{ old.district=''; old.subdistrict=''; old.postal=''; await loadDistricts(); });
      district.addEventListener('change', async()=>{ old.subdistrict=''; old.postal=''; await loadSubdistricts(); });
      subdistrict.addEventListener('change', ()=>{ old.postal=''; loadPostal(); });
      await loadProvinces();
    });
  };
  document.addEventListener('DOMContentLoaded',()=>window.initThaiAddressCascader());
})();
