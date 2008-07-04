if (!Array.prototype.indexOf) {
	Array.prototype.indexOf = function(elt /*, from*/) {
		var len = this.length;
		var from = Number(arguments[1]) || 0;
		from = (from < 0) ? Math.ceil(from) : Math.floor(from);
		if (from < 0) {
			from += len;
		}
		for (; from < len; from++) {
			if (from in this && this[from] === elt) {
				return from;
			}
		}
		return -1;
	};
}

Etano = new function(){};

Etano.record = function(detail) {
	this.value = '';
	this.def_value = 0;
	this.search_value = 0;
	this.accval_id = 0;
	this.after = 0;
	this.is_dirty = true;

	this.get = function() {
		return {accval_id : this.accval_id, value: this.value, after: this.after, def_value: this.def_value, search_value: this.search_value};
	};

	this.set = function(detail) {
		if (typeof detail.accval_id != 'undefined') {
			this.accval_id = detail.accval_id;
		}
		if (typeof detail.value != 'undefined') {
			this.value = detail.value;
		}
		if (typeof detail.after != 'undefined') {
			this.after = detail.after;
		}
		if (typeof detail.def_value != 'undefined') {
			this.def_value = detail.def_value;
		}
		if (typeof detail.search_value != 'undefined') {
			this.search_value = detail.search_value;
		}
	}

	if (typeof detail != 'undefined') {
		this.set(detail);
	}
};


Etano.accvals = function(details) {
	this.container = [];		// the current list of elements
	this.changed_fields = [];	// elements that were changed
	this.new_fields = [];	// elements that were added
	this.deleted_fields = [];	// elements that were deleted
	this.defval_type = null;	// how to render the default value input (as radios or checkboxes)
	this.searchval_type = null;	// how to render the search value input (as radios or checkboxes)

	/**
	 *	Adds a new element in the container at the specified position.
	 *
	 *	@param pos {int} the position to add the new element AFTER. Use -1 to add at the end of the container.
	 *	@param detail {object} the element to add at the specified position
	 *	@returns {int} the position where the element has been added
	 */
	this.add = function(pos, detail) {
		pos = parseInt(pos, 10);
		var rec = new Etano.record(detail);
		// add the record to container - either on the last position or on the specified position
		if (pos==-1) {
			this.container.push(rec);
		} else {
			this.container.splice(pos,0,rec);
		}
		pos = this.container.indexOf(rec);
		// set the .after property of the inserted record
		if (pos>0) {
			for (var i=pos-1;i>=0;i--) {
				if (this.container[i].accval_id != 0) {
					this.container[pos].after=this.container[i].accval_id;
					break;
				}
			}
		}
		// add the record (with the correct .after property) to the new_fields array.
		this.new_fields=[];
		for (var idx in this.container) {
			if (this.container[idx].is_dirty) {
				this.new_fields[idx]=this.container[idx];
			}
		}
//console.log(this.new_fields);
	};

	/**
	 *	Deletes the element at the specified position in the container array
	 *
	 *	@param id {int} the position in the container array to remove
	 */
	this.del = function(pos) {
		pos = parseInt(pos, 10);
		if (typeof this.container[pos] != 'undefined') {
			var rec = this.container[pos];
			this.container.splice(pos, 1);	// remove the element with the index pos.
			if (rec.is_dirty) {	// this means it's a newly added (not yet commited) record
				var pos_new = this.new_fields.indexOf(rec);
				if (pos_new != -1) {
					this.new_fields.splice(pos_new,1);
				}
			} else {	// commited record here.
				this.deleted_fields.push(rec);
				var pos_changed = this.changed_fields.indexOf(rec);
				if (pos_changed != -1) {
					this.changed_fields.splice(pos_changed,1);
				}
			}
		}
	};

	this.change = function(pos, detail) {
		pos = parseInt(pos, 10);
		if (typeof this.container[pos] != 'undefined') {
			var rec = this.container[pos];
			rec.set(detail);
			if (!rec.is_dirty && this.changed_fields.indexOf(rec) == -1) {	// old record not yet changed
				this.changed_fields.push(rec);
			}
			return true;
		}
		return false;
	};

	/**
	 *	Displays the list of values from the container array and the tools to manipulate each value
	 *
	 *	@param parentId {string} the id attribute of the html element inside which to put the list. It is your job to (re)bind
	 *	the tool links to their code.
	 */
	this.render = function(parentId) {
		var towrite='<ul class="accvals_container">'+"\n";
		var i=0;
		for (var idx in this.container) {
			towrite+='<li class="accvals_row';
			if (i==0) {
				towrite+=' first';
			}
			towrite+='">'+"\n";
			towrite+=this.container[idx].value.replace(/</g,'&lt;').replace(/>/g,'&gt;')+"\n";
			towrite+='<span class="tools">'+"\n";
			towrite+='<a href="#" id="edit_'+idx+'" class="accvals_edit icon_link icon_edit" title="Edit value">Edit</a> '+"\n";
			towrite+='<a href="#" id="add_'+idx+'" class="accvals_add icon_link icon_add" title="Add new value after this one">Add after</a> '+"\n";
			towrite+='<a href="#" id="del_'+idx+'" class="accvals_del icon_link icon_del" title="Delete value">Delete</a> '+"\n";
			if (this.defval_type == 'radio') {
				towrite+='<input type="radio" class="radio defval" id="defval_'+idx+'" name="default_value" title="Click to make this the default value for edit"';
				if (this.container[idx].def_value == 1) {
					towrite+=' checked="checked"';
				}
				towrite+='/>';
			} else if (this.defval_type == 'checks') {
				towrite+='<input type="checkbox" class="check defval" id="defval_'+idx+'" title="Click to make this one of the default values for edit"';
				if (this.container[idx].def_value == 1) {
					towrite+=' checked="checked"';
				}
				towrite+='/>';
			}
			if (this.searchval_type == 'radio') {
				towrite+='<input type="radio" class="radio searchval" id="searchval_'+idx+'" name="search_value" title="Click to make this the default value in searches"';
				if (this.container[idx].search_value == 1) {
					towrite+=' checked="checked"';
				}
				towrite+='/>';
			} else if (this.searchval_type == 'checks') {
				towrite+='<input type="checkbox" class="check searchval" id="searchval_'+idx+'" title="Click to make this one of the default values in searches"';
				if (this.container[idx].search_value == 1) {
					towrite+=' checked="checked"';
				}
				towrite+='/>';
			}
			towrite+='</span>'+"\n";
			towrite+='</li>'+"\n";
			i++;
		}
		towrite+='</ul>'+"\n";

		$('#'+parentId).html(towrite);
	}

	this.on_submit = function() {
		var towrite='';
		var temp=[];
		for (i in this.new_fields) {
			temp.push({'value':this.new_fields[i].value,'after':this.new_fields[i].after,'def_value':this.new_fields[i].def_value,'search_value':this.new_fields[i].search_value});
		}
		towrite+='<input type="hidden" name="accvals_new" value="'+escape(JSON.encode(temp))+'" />'+"\n";

		temp=[];
		for (i in this.changed_fields) {
			temp.push({'value':this.changed_fields[i].value,'accval_id':this.changed_fields[i].accval_id,'def_value':this.changed_fields[i].def_value,'search_value':this.changed_fields[i].search_value});
		}
		towrite+='<input type="hidden" name="accvals_changed" value="'+escape(JSON.encode(temp))+'" />'+"\n";

		temp=[];
		for (i in this.deleted_fields) {
			temp.push({'accval_id':this.deleted_fields[i].accval_id});
		}
		towrite+='<input type="hidden" name="accvals_deleted" value="'+escape(JSON.encode(temp))+'" />'+"\n";
		return towrite;
	}

	/**
	 *	Finds the index where a certain property has a certain value
	 *
	 *	@param prop {string} the property to search for
	 *	@param val {mixed} the value to match the property against
	 *	@returns {int} the index where the property was found or -1 if no match.
	 */
	this.prop_find = function(prop, val) {
		for (var idx in this.container) {
			if (typeof this.container[idx] == 'record' && typeof this.container[idx][prop] != 'undefined' && this.container[idx][prop] == val) {
				return idx;
			}
		}
		return -1;
	}

	this.clear_dirty = function() {
		this.changed_fields = [];
		this.new_fields = [];
		this.deleted_fields = [];
		for (var i in this.container) {
			this.container[i].is_dirty = false;
		}
	}

	this.set_defval_type = function(str) {
		// if there are more values checked when we switch to radio, keep only the first one checked
		if (str == 'radio') {
			var first_found = false;
			for (var i in this.container) {
				if (first_found) {
					this.container[i].def_value = 0;
				}
				if (this.container[i].def_value == 1) {
					first_found = true;
				}
			}
		}
		this.defval_type = str;
	}

	this.set_searchval_type = function(str) {
		// if there are more values checked when we switch to radio, keep only the first one checked
		if (str == 'radio') {
			var first_found = false;
			for (var i in this.container) {
				if (first_found) {
					this.container[i].search_value = 0;
				}
				if (this.container[i].search_value == 1) {
					first_found = true;
				}
			}
		}
		this.searchval_type = str;
	}

	this.set_defval = function(idx,state) {
		var rec = this.container[idx];
		if (this.defval_type == 'radio') {
			for (var i in this.container) {
				this.container[i].def_value = 0;
			}
			this.container[idx].def_value = 1;
		} else if (this.defval_type == 'checks') {
			this.container[idx].def_value = 0+state;
		}
		if (!rec.is_dirty && this.changed_fields.indexOf(rec) == -1) {	// old record not yet changed
			this.changed_fields.push(this.container[idx]);
		}
//console.log(this.container);
	}

	this.set_searchval = function(idx,state) {
		var rec = this.container[idx];
		if (this.searchval_type == 'radio') {
			for (var i in this.container) {
				this.container[i].search_value = 0;
			}
			this.container[idx].search_value = 1;
		} else if (this.searchval_type == 'checks') {
			this.container[idx].search_value = 0+state;
		}
		if (!rec.is_dirty && this.changed_fields.indexOf(rec) == -1) {	// old record not yet changed
			this.changed_fields.push(this.container[idx]);
		}
	}

	if (typeof details != 'undefined') {
		for (var idx in details) {
			this.add( -1, details[idx] );
		}
		this.clear_dirty();
	}
}
