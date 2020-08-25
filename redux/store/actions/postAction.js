import * as types from '../types';

export const fetchposts = () => async dispatch => {
    fetch('https://jsonplaceholder.typicode.com/posts')
        .then(response => response.json())
        .then(json => {
            dispatch({type: types.GET_POSTS, payload: json})
        })
}

export const incrementCounter = () => async dispatch => {
    dispatch({type: types.INCREMENT_COUNTER})
}