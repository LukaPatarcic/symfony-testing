import {useDispatch, useSelector} from "react-redux";
import {useEffect} from "react";
import {fetchposts} from "../store/actions/postAction";

export default function Home() {

    const dispatch = useDispatch();
    const {posts,loading} = useSelector(state => state.posts);
    console.log(loading);

    useEffect(() => {
        dispatch(fetchposts());
    },[]);

    return (
        <div>{posts.map((post,index) => <p key={index}>{post}</p>)}</div>
    )

}
