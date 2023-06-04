
var where = document.getElementById('user');
if(where){
var sel = document.createElement('select');
sel.style.display = 'unset';
sel.classList.add('col', 's4');
fetch('API/users')
  .then(response => response.json())
  .then(data => {
    data.forEach(el => {
        var op = document.createElement('option');
        op.value = el.ID;
        op.innerText = el.Name
        sel.appendChild(op);
    });
    where.appendChild(sel);
  });
sel.addEventListener('click', function(e){
    var child = where.children;
    for (let i = 0; i < child.length; i++) {
        const element = child[i];
        if(element.tagName == "TABLE"){
            where.removeChild(element);
        }
    }
    fetch('API/user?ID='+e.target.value)
    .then(response => response.json())
    .then(data => {
        var tab = document.createElement('table');
        var tha = document.createElement('thead');
        var tr = document.createElement('tr');
        tr.innerHTML='<th></th>';
        for (const m of Object.values(data)) {  
            var th = document.createElement('th');
            th.innerText = m.m;
            tr.appendChild(th);
        }
        var tbod = document.createElement('tbody');
        var tr2 = document.createElement('tr');
        tr2.innerHTML = "<td>BC <br> AB <br> Total</td>"
        for (const items of Object.values(data)) {  
            var output = `<td>$${items.BC} <br> $${items.AB} <br> $${items.total}</td>`;
            tr2.innerHTML += output;
        }
        tbod.appendChild(tr2);
        tha.appendChild(tr);
        tab.appendChild(tha);
        tab.appendChild(tbod);
        where.appendChild(tab);
    })
})
}
