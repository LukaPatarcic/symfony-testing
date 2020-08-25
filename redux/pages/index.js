import {useDispatch, useSelector} from "react-redux";
import {useEffect} from "react";
import {fetchposts,incrementCounter} from "../store/actions/postAction";

export default function Home() {

    const dispatch = useDispatch();
    const {posts,loading,counter} = useSelector(state => state.posts);

    useEffect(() => {
        dispatch(fetchposts());
    },[]);

    return (
        <div>
            <button onClick={() => dispatch(incrementCounter())}>Increment</button>
            <p>{counter}</p>
            {loading ?
                'Loading...'
                :
                posts.map((post,index) => <p key={index}>{post.title}</p>)
            }
        </div>
    )

}
