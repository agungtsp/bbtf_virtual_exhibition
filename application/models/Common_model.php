<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_model extends CI_Model
{
    
    function __construct()
    {
       parent::__construct();
    }

    /**
    * Records Data with Paging
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table 	  : Nama table (string)
    * @param      select 	  : Column yang di select (array atau string)
    * @param      where 	  : Kondisi where (array atau string)
    * @param      limit 	  : Limit data (int)
    * @param      offset 	  : Offset data (int)
    * @param      order_field : Nama column order by (string)
    * @param      order_type  : Tipe sort (string; ASC atau DESC)
    * @param      $is_total   : Status return num_rows() atau result data (int; 1 untuk num_rows())
    * @return     <array> data records from table
    */
    function records($where = array(), $is_total = 0)
    {
		$this->where = $where;

		if ($where['select'])
		{
			if (is_array($where['select']))
			{
				$select = implode(', ', $where['select']);
			}
			else
			{
				$select = $where['select'];
			}
		}
		else
		{
			// Jika tidak di select manual, defaultnya SELECT ALL
			$select = 'a.*';
		}

		$table       = $where['table'];
		$table_as    = $table.' a';
		$select      = ($where['select']) ? $where['select'] : 'a.*';
		$limit       = ($where['limit']) ? $where['limit'] : 10;
		$offset      = ($where['offset']) ? $where['offset'] : 0;
		$order_field = ($where['order_field']) ? $where['order_field'] : 'id';
		$order_type  = ($where['order_type']) ? $where['order_type'] : 'DESC';
        
		unset($where['table'], $where['limit'], $where['offset'], $where['order_field'], $where['order_type']);
		if ($where['where'])
		{
			if (is_array($where['where'])) 
			{
		        where_grid($where['where'], $alias);
			}
			else
			{
				$this->db->where($where['where']);
			}
		}
		
		$this->db->select($select);
		if ($is_total == 0)
		{

			$this->db->limit($limit, $offset);
		}

		$this->db->order_by($order_field, $order_type);
		$query = $this->db->get($table_as);	
		if ($query)
		{
			if ($is_total == 0)
			{
				$data = $query->result_array();
			}
			else
			{
				return $query->num_rows();
			}

			$where                = $this->where;
			$ttl_row              = $this->records($where, 1);

			return ddi_grid_api($data, $ttl_row);
		} 
		else 
		{
			// Jika query gagal, di return error message-nya
			$return['query_error']         = TRUE;
			$return['query_error_message'] = $this->db->error();

			return $return;
		}
    }

    /**
    * Get Data
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      table 	  : Nama table (string)
    * @param      select 	  : Column yang di select (array atau string)
    * @param      where 	  : Kondisi where (array atau string)
    * @param      limit 	  : Limit data (int)
    * @param      offset 	  : Offset data (int)
    * @param      order_field : Nama column order by (string)
    * @param      order_type  : Tipe sort (string; ASC atau DESC)
    * @param      $is_total   : Status return num_rows() atau result data (int; 1 untuk num_rows())
    */
	function find_by($config=array(), $is_single_row = 0, $is_total = 0)
	{
		$table       = $config['table'];
		$table_as    = $config['table'].' a';

		if ($config['select'])
		{
			if (is_array($config['select']))
			{
				$select = implode(', ', $config['select']);
			}
			else
			{
				$select = $config['select'];
			}
		}
		else
		{
			$select = 'a.*';
		}

		$select      = $config['select'] ? $config['select'] : 'a.*';
		$limit       = $config['limit'] ? $config['limit'] : 10;
		$order_field = $config['order_field'] ? $config['order_field'] : 'a.id';
		$order_type  = $config['order_type'] ? $config['order_type'] : 'DESC';
		if ($config['where'])
		{
			if (is_array($config['where'])) 
			{
				$alias       = $config['alias'];
		        $whre = where_grid($config['where'], $alias);
			}
			else
			{
				$this->db->where($config['where']);
			}
		}

		if ($is_total == 0 && $config['limit'])
		{
			$this->db->limit($limit);
		}

		$this->db->select($select);

		
		/* editted by hana 
			--- cek jika order field menggunakan default (a.id/id) 
				maka, cek apakah field tersedia di db.
		*/

		// if ($is_single_row == 0 && $is_total == 0)
		if (($order_field == 'a.id' || $order_field == 'id') 
				&& $this->db->field_exists($order_field, $table_as) 
			|| ($order_field != 'a.id' && $order_field != 'id')
			)
		{
			$this->db->order_by($order_field, $order_type);
		}

		$query = $this->db->get($table_as);

		if ( ! $query)
		{
			$return['query_error']         = TRUE;
			$return['query_error_message'] = $this->db->error();

			return $return;
		}
		else
		{
			if ($is_single_row == 1) 
			{
				return $query->row_array();
			}
			else
			{
				if ($is_total == 1) 
				{
					return $query->num_rows();
				}
				else 
				{
					return $query->result_array();	
				}
			}
		}
	}

	/**
    * Insert data to table
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      $table  : Nama table (string)
    * @param      $data	  : Data insert (array)
    * @return     <string> Last inserted id
    */
	function insert($table, $data)
	{
		$this->db->insert($table, $data);
		$id = $this->db->insert_id();
		return $id;
	}

	/**
    * Update data
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      $table  : Nama table (string)
    * @param      $data	  : Data insert (array)
    * @param      $where  : Kondisi where (string atau array)
    * @return     <boolean> TRUE if update success or FALSE if update failed
    */
	function update($table, $data, $where)
    {
    	if (is_array($where))
    	{
    		where_grid($where);
    	}
    	else
    	{
    		$this->db->where($where);
    	}

        $update = $this->db->update($table, $data);
        return $update;
    }

    /**
    * Delete data
    * @author     Siti Hasuna <sh.hanaaa@gmail.com>
    * @param      $table  	    : Nama table (string)
    * @param      $where  	    : Kondisi where (string atau array)
    * @param      $is_permanent : Status delete, berdasarkan is_delete atau langsung dihapus permanen
    * @return     <boolean> TRUE if delete success or FALSE if delete failed
    */
    function delete($table, $where, $is_permanent = 1)
    {
    	if ($is_permanent == 0)
    	{
    		$this->update($table, array('is_delete' => 1), $where);
    	}
    	else
    	{
	    	if (is_array($where))
	    	{
	    		where_grid($where);
	    	}
	    	else
	    	{
	    		$this->db->where($where);
	    	}

	    	where_grid($where);
	        $delete = $this->db->delete($table);

	        return $delete;
    	}
    }
}

/* End of file Common_model.php */
/* Location: ./application/modules/v1/common/models/Common_model.php */