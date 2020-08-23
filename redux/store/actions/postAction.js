import * as types from '../types';

export const fetchposts = () => async dispatch => {
    // make api call here
    dispatch({
        type: types.GET_POSTS,
        payload: ['1st post', '2nd post']
    })
}