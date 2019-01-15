const db = require('./db');
const Helper = require('./helper');
const STATUS = require('./status');

const User = require('./user');

const PostLike = {

  /**
   * Toggle add/remove like from specified post
   *
   * @param userId
   * @param postId
   *
   * @returns {Promise} which returns object:
   *   {success, action, postId}
   */
  async update(userId, postId){
    try {
      if (!userId || !postId) {
        throw {
          success: false,
          status: STATUS.INVALID_INPUT_PARAMETERS,
          message: 'No userId or postId specified. Request must includes object like {hash, postId}'
        };
      }

      const getLikeIdResult = await this._getId(userId, postId);

      // if like exists, remove like
      if (getLikeIdResult.id) {
        await this._remove(getLikeIdResult.id);
        return {success: true, status: STATUS.OK, postId, action: "remove"}
      }

      // or add new post like
      await this._add(userId, postId);

      // check if user exists and get his id
      const getUserResult = await User.getByPostId(postId);
      if (getLikeIdResult.status === STATUS.NOT_FOUND) {
        return {success: true, status: STATUS.OK, postId, action: "add", postCreatorId: false};
      }

      return {success: true, status: STATUS.OK, postId, action: "add", postCreatorId: getUserResult.user.userId};
    } catch (err) {
      if (err.status === STATUS.INTERNAL_ERROR || !err.status) {
        console.log(err);
        return {success: false, status: STATUS.INTERNAL_ERROR, message: 'Server error'};
      }
      return err;
    }
  },

  /**
   * Get like id by post id and user id
   *
   * @param userId
   * @param postId
   * @returns {Promise} which returns {success, status, id}
   * @private
   */
  _getId(userId, postId){
    return new Promise((res, rej) => {
      if (!userId || !postId) {
        return rej({
          success: false,
          status: STATUS.INTERNAL_ERROR,
          message: 'PostLike._getLikeId error: no userId or postId id provided'
        });
      }

      db.query(
        'SELECT id FROM tbl_user_post_like WHERE postId = ? AND userId = ?;',
        [postId, userId],
        (err, rows) => {
          if (err) return rej({
            success: false,
            status: STATUS.INTERNAL_ERROR,
            message: "Error in PostLike._getLikeId:\n" + err
          });

          if (!rows.length) {
            return res({success: true, status: STATUS.OK, id: false});
          }

          return res({success: true, status: STATUS.OK, id: rows[0].id});
        })
    })
  },

  /**
   * Remove from DB like by it id
   *
   * @param id
   * @returns {Promise} which returns {success, status, id}
   * @private
   */
  _remove(id){
    return new Promise((res, rej) => {
      if (!id) {
        return rej({
          success: false,
          status: STATUS.INTERNAL_ERROR,
          message: 'PostLike._remove error: no id provided'
        });
      }

      db.query("DELETE FROM tbl_user_post_like WHERE `id` = ?;", [id], err => {
        if (err) return rej({
          success: false,
          status: STATUS.INTERNAL_ERROR,
          message: "Error in PostLike._remove:\n" + err
        });

        return res({success: true, status: STATUS.OK, id});
      });
    })
  },

  _add(userId, postId){
    return new Promise((res, rej) => {
      if (!userId || !postId) {
        return rej({
          success: false,
          status: STATUS.INTERNAL_ERROR,
          message: 'PostLike._add error: no userId or postId id provided'
        });
      }

      // add like
      db.query(
        'INSERT INTO tbl_user_post_like (postId, userId) VALUES (?, ?);',
        [postId, userId, postId],
        (err, row) => {
          if (err) return rej({
            success: false,
            status: STATUS.INTERNAL_ERROR,
            message: "Error in PostLike._remove:\n" + err
          });

          return res({success: true, status: STATUS.OK, id: row.insertId});
        });
    })
  },

  /**
   * Get all post likes by post id,
   * Get number of likes
   * Get info about all of users who liked post
   *
   * @param postId
   * @returns {Promise}
   */
  get(postId){
    return new Promise((res, rej) => {
      if (!postId) {
        return rej({
          success: false,
          status: STATUS.INVALID_INPUT_PARAMETERS,
          message: 'getPostLikes error: No postId id provided. Request must includes object like {postId}'
        });
      }

      db.query(
        'SELECT COUNT(*) AS likesTotal FROM tbl_user_post_like WHERE postId = ?;\
         SELECT \
           likes.time, \
           user.userId,\
           user.userNickname,\
           user.userPhoto\
         FROM tbl_user_post_like AS likes\
         LEFT JOIN tbl_user AS user ON likes.userId = user.userId\
         WHERE postId = ?\
         ORDER BY time DESC;',
        [postId, postId],
        (err, rows) => {
          if (err) return rej({
            success: false,
            status: STATUS.INTERNAL_ERROR,
            message: "Error in PostLike.get:\n" + err
          });

          const likesTotal = rows[0][0].likesTotal;

          const users = rows[1];
          for (let i = 0; i < users.length; i++) {
            users[i] = Helper.handleUserName(users[i])
          }
          return res({success: true, status: STATUS.OK, postId, likesTotal, users});
        });
    })
  },
};

module.exports = PostLike;